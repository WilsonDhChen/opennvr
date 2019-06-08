_config.gb28181.settransfer(true)
_app.setworkmode("cdn")

_workmode = _app.getworkmode()

_nvraddr = _app.getinistring("transcodesrv_api_addr","addr")
_nvrport= _app.getiniint("transcodesrv_api_addr","port")
_nvrenable=_app.getinibool("transcodesrv_api_addr","enable")

_gb28181addr = _app.getinistring("gb28181_api_addr","addr")
_gb28181port= _app.getiniint("gb28181_api_addr","port")
_gb28181enable=_app.getinibool("gb28181_api_addr","enable")

_domain = _config.getdomain()
_streamapp = _config.getapp()
_domain_support = _config.domainsupport()
----------------------------------------------------------------------------------
function OnAsynHttpReturn(key, status, content, requrl)
	_log.print(_log.info,"key="..key..",status="..status..",content="..content..",requrl="..requrl .."\n")
end
----------------------------------------------------------------------------------
function NvrGb28181IdToStreamId(gb28181id)
	if( not _nvrenable )
	then
		return false
	end
	
	local url = "http://".._nvraddr..":".._nvrport.."/gb28181id2streamid?gb28181id="..gb28181id
	_log.print(_log.info,url .."\n")
	local httpstatus, content = _lua_http_call(url)
	if( httpstatus ~= 200 )
	then
	return false
	end
	
	local cjson = require "cjson"
	local data = cjson.decode(content);
	if( data["return"] ~= 0 )
	then
		return false
	end
	
	return data["streamid"]
end
-----------------------------------------------------------------------------------
function AsynNvrCallStartStream(streamid )
	if( not _nvrenable )
	then
		return false
	end
    local url = "http://".._nvraddr..":".._nvrport.."/startfromcdn?streamid="..streamid
    _log.print(_log.info,url .."\n")
    _lua_asyn_http_call("",url);
end
-----------------------------------------------------------------------------------
function NvrCallStartStream(domain,streamapp , stream , gb28181id)
	if( not _nvrenable )
	then
		return false
	end
	
	local url = "http://".._nvraddr..":".._nvrport.."/startfromcdn?app="..streamapp.."&streamid="..stream.."&gb28181id="..gb28181id
	_log.print(_log.info,url .."\n")
	local httpstatus, content = _lua_http_call(url)
	if( httpstatus ~= 200 )
	then
	return false
	end
	local cjson = require "cjson"
	local data = cjson.decode(content);
	if( data["return"] ~= 0 )
	then
		return false
	end
	
	if( data["type"]  == "push" )
	then
		return false
	end
	
	if( data["type"]  == "gb28181" )
	then
		if( data["gb28181output"]  ~= 1 )
		then
			return false
		end
		
		local gb28181inputid = data["gb28181inputid"]
		local transfermode , name = _app.gb28181transfermode(gb28181inputid )
		if( transfermode == "")
		then
			_log.print(_log.error, stream.." ["..gb28181inputid.."] not found in gb28181 server\n")
			return false
		end
		_app.callgb28181input(gb28181inputid,streamapp,stream,data["gb28181outputid"],transfermode,name, true)
	end
	
	return true
end

-----------------------------------------------------------------------------------
function Gb28181Notify(domain,streamapp , stream , gb28181id,gb28181id_input,name,status,event)
	if( not _gb28181enable  or  gb28181id == "" )
	then
	return false
	end
	local url = "http://".._gb28181addr..":".._gb28181port.."/notify?domain="..domain.."&app="..streamapp.."&streamid="..stream
	local cjson = require "cjson"
	local gbjson = {}
	gbjson["app"] = streamapp
	gbjson["streamid"] = stream
	gbjson["gb28181id"] = gb28181id
	gbjson["gb28181id_input"] = gb28181id_input
	gbjson["name"] = name
	gbjson["status"] = status
	gbjson["event"] = event
	
	local postdata = cjson.encode(gbjson);
	_log.print(_log.info,postdata.."\n")
	_lua_asyn_http_call("Gb28181Notify",url,postdata);
	
	return true
end

---------------------------------------------------------
local redis_connect_info = {};
redis_connect_info.host = _app.getinistring("redis","host");
redis_connect_info.port = _app.getiniint("redis","port");
redis_connect_info.pwd = _app.getinistring("redis","pass");
redis_connect_info.timeout = _app.getiniint("redis","timeout");

_enable_redis = _app.getinibool("redis","enable");



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
	
    if not _enable_redis then
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
	
    if not _enable_redis then
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

    if ( _lua_strcmpi(domain, cdn_domain) ~= 0  ) then
        _stream.seterror(ctx,"not a play domain");
        return -1;
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

    
    local streamapp = _stream.getapp(ctx);
	local streamid = _stream.getstream(ctx);
    local domain = _stream.getdomain(ctx);
    
    local matchPlan = false
    
    if( _domain_support )
    then
        if( _domain == domain and _streamapp == streamapp )
        then
            matchPlan = true;
        end
    else
        if( _streamapp == streamapp )
        then
            matchPlan = true;
        end
    end
    
	
	if( matchPlan )
	then
		Gb28181Notify( domain, streamapp, streamid, _stream.getgb28181id(ctx), _stream.getgb28181id_input(ctx),_stream.getname(ctx),  "ON", "ADD")
		return 0
    end
    
    --[[ 
    ----为了获得更好的用户体验来流直接推到边缘服务器，这不是必须的

	local edge_servers = _lua_textfile2array("./domains/"..domain.."/slaves.txt")
	if type(edge_servers) == "table" then 
		for  i, addr in pairs(edge_servers) do    
		   _app.vtspushto(domain,streamapp,streamid,addr,0,false,true)
		end  
	end 
    --]]
	return 0 ;
end

----------------------------------------------------------------------------------
function OnStreamPublishClosed( ctx )
	---通知国标服务器，视频下线
	local streamapp =  _stream.getapp(ctx);
	local domain = _stream.getdomain(ctx);
    local matchPlan = false
    
    if( _domain_support )
    then
        if( _domain == domain and _streamapp == streamapp )
        then
            matchPlan = true;
        end
    else
        if( _streamapp == streamapp )
        then
            matchPlan = true;
        end
    end
    
	if( matchPlan )
	then
		Gb28181Notify( domain, streamapp, _stream.getstream(ctx), _stream.getgb28181id(ctx), _stream.getgb28181id_input(ctx),_stream.getname(ctx),  "OFF", "ADD")
		return 0
	end

	return 0 ;
end


-----------------------------------------------------------------------------------

function OnGetSourceStreamURL(domain, streamapp , stream , gb28181id, par ,protocal)

    local matchPlan = false
    
    if( _domain_support )
    then
        if( _domain == domain and _streamapp == streamapp )
        then
            matchPlan = true;
        end
    else
        if( _streamapp == streamapp )
        then
            matchPlan = true;
        end
    end
    

	if( not matchPlan )
	then
		return "callcdn"
	end

	local transfermode, name , streamid = _app.gb28181transfermode(stream)
	if( transfermode == "")
	then
		_log.print(_log.warning, stream.." not found in gb28181 server , try to check in transcodesrv\n")
		if( NvrCallStartStream(domain,streamapp,stream,gb28181id) )
		then
            _log.print(_log.info, stream.." found in transcodesrv server\n")
			return "" ;
		end
		return nil
	end
        if( transfermode == "callnvr")
        then
            AsynNvrCallStartStream(streamid)
        else
            _log.print(_log.info, stream.." transfermode="..transfermode..",name="..name.."\n")
            _app.callgb28181input(stream,sapp,"","",transfermode,name)
        end
        
	return "" ;

end

----------------------------------------------------------------------------
function Gb28181IdToStreamId( gb28181id )
	return NvrGb28181IdToStreamId( gb28181id )
end
-----------------------------------------------------------------------
function StreamIdToGb28181Id( stream )

end
-----------------------------------------------------------------------
function IsStreamExist(domain, streamapp, stream )
    
    local matchPlan = false
    if( _domain_support )
    then
        if( _domain == domain and _streamapp == streamapp )
        then
            matchPlan = true;
        end
    else
        if( _streamapp == streamapp )
        then
            matchPlan = true;
        end
    end
    

	if( not matchPlan )
	then
		return 0
	end
    
	local transfermode, name , streamid = _app.gb28181transfermode(stream)
	if( transfermode == "")
	then
		_log.print(_log.warning, stream.." not found in gb28181 server , try to check in transcodesrv\n")
		if( not NvrCallStartStream(domain,streamapp,stream,"") )
		then
			return 0
		end
            _log.print(_log.info, stream.." found in transcodesrv server\n")
		return 1
    else
        if( transfermode == "callnvr")
        then
            AsynNvrCallStartStream(streamid)
        else
            _log.print(_log.info, stream.." transfermode="..transfermode..",name="..name.."\n")
            _app.callgb28181input(stream,sapp,"","",transfermode,name)
        end
    end
	return 1 ;
	
end














