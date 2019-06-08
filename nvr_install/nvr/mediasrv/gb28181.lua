
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

end
----------------------------------------------------------------------------------
function NvrGb28181IdToStreamId(gb28181id)
	if( not _nvrenable )
	then
		return false
	end
	
	local url = "http://".._nvraddr..":".._nvrport.."/gb28181id2streamid?gb28181id="..gb28181id
	_log.print(_log.info,url .."\n")
	httpstatus, content = _lua_http_call(url)
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
function NvrCallStartStream(domain,streamapp , stream , gb28181id)
	if( not _nvrenable )
	then
		return false
	end
	
	local url = "http://".._nvraddr..":".._nvrport.."/startfromcdn?app="..streamapp.."&streamid="..stream.."&gb28181id="..gb28181id
	_log.print(_log.info,url .."\n")
	httpstatus, content = _lua_http_call(url)
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
		local transfermode = _app.gb28181transfermode(gb28181inputid )
		if( transfermode == "")
		then
			_log.print(log.error, stream.." ["..gb28181inputid.."] not found in gb28181 server\n")
			return false
		end
		_app.callgb28181input(gb28181inputid,streamapp,stream,data["gb28181outputid"],transfermode)
	end
	
	return true
end

-----------------------------------------------------------------------------------
function Gb28181Notify(domain,streamapp , stream , gb28181id,name,status,event)
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
	gbjson["name"] = name
	gbjson["status"] = status
	gbjson["event"] = event
	
	local postdata = cjson.encode(gbjson);
	_log.print(_log.info,postdata.."\n")
	_lua_asyn_http_call("Gb28181Notify",url,postdata);
	
	return true
end
-----------------------------------------------------------------------------------
function OnStreamPublished( ctx )
	---通知国标服务器，视频上线
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
		Gb28181Notify( domain, streamapp, _stream.getstream(ctx), _stream.getgb28181id(ctx),_stream.getname(ctx),  "OFF", "ADD")
		return 0
	end

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
		Gb28181Notify( domain, streamapp, _stream.getstream(ctx), _stream.getgb28181id(ctx),_stream.getname(ctx),  "OFF", "ADD")
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

	local transfermode = _app.gb28181transfermode(stream)
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
		_log.print(_log.info, stream.." transfermode="..transfermode.."\n")
		_app.callgb28181input(stream,sapp,"","",transfermode)
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
    
	local transfermode = _app.gb28181transfermode(stream)
	if( transfermode == "")
	then
		_log.print(_log.warning, stream.." not found in gb28181 server , try to check in transcodesrv\n")
		if( not NvrCallStartStream(domain,streamapp,stream,"") )
		then
			return 0
		end
        _log.print(_log.info, stream.." found in transcodesrv server\n")
		return 1
	end
	_log.print(_log.info, stream.." transfermode="..transfermode.."\n")
	_app.callgb28181input(stream,sapp,"","",transfermode)
	return 1 ;
	
end


