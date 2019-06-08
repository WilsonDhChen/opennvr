local redis_connect_info = {};
redis_connect_info.host = _app.getinistring("redis","host");
redis_connect_info.port = _app.getiniint("redis","port");
redis_connect_info.pwd = _app.getinistring("redis","pass");
redis_connect_info.timeout = _app.getiniint("redis","timeout");

_domain = _config.getdomain();

_cjson = require "cjson"
RedisPool = require 'redispool'
_redispool = RedisPool:new(redis_connect_info)
_redispool:push(_redispool:pop())


function GetGlobalConfig()
    local rediscli = _redispool:pop()
    if rediscli == nil then
        return nil;
    end
    local globalset_json  = rediscli:get("_globalset")
    _redispool:push(rediscli)
    
    local setjson = _cjson.decode(globalset_json);
    return setjson;
    
end


function GetDmJson( domain )
    local rediscli = _redispool:pop()
    if rediscli == nil then
        return nil;
    end
    local dmkey = rediscli:get(domain)
    if dmkey == nil then
        _redispool:push(rediscli)
        return nil;
    end
    local dminfo_json  = rediscli:get("dm"..dmkey)
    _redispool:push(rediscli)
    
    local dminfo = _cjson.decode(dminfo_json);
    return dminfo;
end

function GetDmsetJson( domain )
    local rediscli = _redispool:pop()
    if rediscli == nil then
        return nil;
    end
    local dmkey = rediscli:get(domain)
    if dmkey == nil then
        _redispool:push(rediscli)
        return nil;
    end
    local dminfo_json  = rediscli:get("dmset"..dmkey)
    _redispool:push(rediscli)
    
    
    local dminfo = _cjson.decode(dminfo_json);
    return dminfo;
end


------------------------------------------------
function OnStreamWillPublish( ctx )
	if _stream.isfromcdn(ctx) then 
		return 0 ;
	end 
	
	local domain = _stream.getdomain(ctx);
    
    if( type(_domain) == "string" and #_domain > 0 ) then
        if( _lua_strcmpi(_domain,domain) == 0 ) then
            return 0;
        end
   	end
    
    
    ----查找播放域名，找不到就禁止
    local dminfo = GetDmJson(domain);
    if( type(dminfo) ~= "table") then
        _stream.seterror(ctx,"invalid user");
        return -1;
    end
    
    
    local cdn_domain = dminfo["cdn_domain"];
    if( type(cdn_domain) ~= "string" or #cdn_domain < 1 ) then
        _stream.seterror(ctx,"cdn domain configure error");
        return -1;
    end
       
    if ( _lua_strcmpi(domain, dminfo["src_domain"]) ~= 0  ) then
        _stream.seterror(ctx,"not a push domain");
        return -1;
    end
    
    ----域名已经被停止
    if _lua_tobool(dminfo["is_disable"])  then
        return -1;
    end
    _stream.setdomain(ctx,cdn_domain);
    
    ----检查推流的key
    local push_key_name = dminfo["push_key_name"];
    local push_key_value = dminfo["push_key_value"];
    
    if( type(push_key_name) == "string" and #push_key_name > 0 ) then
        local urlKeyValue = _stream.geturiquery_var(ctx,push_key_name);
        if( _lua_strcmpi(push_key_value,urlKeyValue) ~= 0 ) then
            return -1;
        end
   	end
    
    
    
	return 0 ;
end

----------------------------------------------------------
function GlobalPlayCheck(ctx)

    local globalset = GetGlobalConfig();
    if( type(globalset) ~= "table") then
        return -1;
    end

    ----检查播放Key
    local pull_key_name = globalset["pull_key_name"];
    local pull_key_value = globalset["pull_key_value"];

    if( type(pull_key_name) == "string" and #pull_key_name > 0 ) then
        local urlKeyValue = _stream.geturiquery_var(ctx,pull_key_name);
        if( _lua_strcmpi(pull_key_value,urlKeyValue) == 0 ) then
            return 0;
        end
    end
       

    ----检查播放oldKey
    local old_pull_key_name = globalset["old_pull_key_name"];
    local old_pull_key_value = globalset["old_pull_key_value"];

    if( type(old_pull_key_name) == "string" and #old_pull_key_name > 0 ) then
        local urlKeyValue = _stream.geturiquery_var(ctx,old_pull_key_name);
        if( _lua_strcmpi(old_pull_key_name,urlKeyValue) == 0 ) then
            return 0;
        end
    end

    return -1
end
----------------------------------------------------------
function OnStreamWillPlay(  ctx )
	if  _stream.isfromcdn(ctx)  then 
		return 0 ;
	end 
	
	local domain = _stream.getdomain(ctx);

    if( type(_domain) == "string" and #_domain > 0 ) then
        if( _lua_strcmpi(_domain,domain) == 0 ) then
            return 0;
        end
   	end
    
    ----查找播放域名，找不到就禁止
    local dminfo = GetDmJson(domain);
    if( type(dminfo) ~= "table") then
        _stream.seterror(ctx,"not found domain");
        return -1;
    end
    

    
    local cdn_domain = dminfo["cdn_domain"];
    if( type(cdn_domain) ~= "string" or #cdn_domain < 1 ) then
        _stream.seterror(ctx,"cdn domain configure error");
        return -1;
    end


       

    
    ----域名已经被停止
    if _lua_tobool(dminfo["is_disable"])  then
        _stream.seterror(ctx,"your domain disabled");
        return -1;
    end
  
    ----检测SSL设置
    if( _stream.isvod(ctx) ) then
        if ( _lua_strcmpi(domain, dminfo["pb_domain"]) ~= 0  ) then
            _stream.seterror(ctx,"not a playback domain");
            return -1;
        end
        
        _stream.setdomain(ctx,cdn_domain)
        if ( _lua_tobool(dminfo["pb_ssl_only"]) and (not _stream.isssl(ctx) ) )  then
            _stream.seterror(ctx,"only allow access via SSL");
            return GlobalPlayCheck(ctx);
        end
        
        domain = cdn_domain ;
    else
        if ( _lua_strcmpi(domain, cdn_domain) ~= 0  ) then
            _stream.seterror(ctx,"not a liveplay domain");
            return -1;
        end
    
        if ( _lua_tobool(dminfo["cdn_ssl_only"]) and (not _stream.isssl(ctx) ) )  then
            _stream.seterror(ctx,"only allow access via SSL");
            return GlobalPlayCheck(ctx);
        end
    end


    
    ----检测黑名单
    local black_list = dminfo["black_list"];
    if( type(black_list) == "string" and #black_list > 0 ) then
        if( _stream.referhost_matchdomainlist(ctx,black_list) ) then
            _stream.seterror(ctx,"blocked!!! code B");
            return GlobalPlayCheck(ctx);
        end
    end
    
    ----检测白名单
    local white_list = dminfo["white_list"];
    if( type(white_list) == "string" and #white_list > 0 ) then
        if( not _stream.referhost_matchdomainlist(ctx,white_list) ) then
            _stream.seterror(ctx,"blocked!!! code W");
            return GlobalPlayCheck(ctx);
        end
    end    
    
    ----检查播放Key
    local pull_key_name = dminfo["pull_key_name"];
    local pull_key_value = dminfo["pull_key_value"];
    
    if( type(pull_key_name) == "string" and #pull_key_name > 0 ) then
        local urlKeyValue = _stream.geturiquery_var(ctx,pull_key_name);
        if( _lua_strcmpi(pull_key_value,urlKeyValue) ~= 0 ) then

            _stream.seterror(ctx,"blocked!!! code K");

            local old_pull_key_name = dminfo["old_pull_key_name"];
            local old_pull_key_value = dminfo["old_pull_key_value"];
            
            if( type(old_pull_key_name) == "string" and #old_pull_key_name > 0 ) then
                local urlKeyValue2 = _stream.geturiquery_var(ctx,old_pull_key_name);
                if( _lua_strcmpi(old_pull_key_value,urlKeyValue2) ~= 0 ) then
                    return GlobalPlayCheck(ctx);
                end
            else
                return GlobalPlayCheck(ctx);
            end

            
        end
   	end
    
    
	return 0;
end
------------------------------------------------
function OnStreamPublished( ctx )

	if  _stream.isfromcdn(ctx) then 
		return 0 ;
	end 
    
    --[[ 
    ----为了获得更好的用户体验来流直接推到边缘服务器，这不是必须的
	local streamapp = _stream.getapp(ctx);
	local streamid = _stream.getstream(ctx);
	local domain = _stream.getdomain(ctx);
	local edge_servers = _lua_textfile2array("./domains/"..domain.."/slaves.txt")
	if type(edge_servers) == "table" then 
		for  i, addr in pairs(edge_servers) do    
		   _app.vtspushto(domain,streamapp,streamid,addr,0,false,true)
		end  
	end 
    --]]
	return 0 ;
end
















