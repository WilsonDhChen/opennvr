
require("string")

_DBNAME="livestream"
_DBUSER="root"
_DBPWD="123456"
_DBADDR="127.0.0.1"
_DBPORT=3306


_mtxDb = mtx.create()


---MySQL
envMysql = luasql.mysql.mysql();
conn , errMysql= envMysql:connect(_DBNAME,_DBUSER,_DBPWD,_DBADDR,_DBPORT);


if( errMysql ~=nil)
then 
	print(errMysql);
	app.initfailed(-1)
	return 
end


function exesql(con,sql)
	mtx.lock(_mtxDb)

	if ( con == nil )
	then
		mtx.unlock(_mtxDb)
		return nil, "mysql not connected"
	end
	
	local cursor,errorString = con:execute(   sql      )

	if( errorString ~= nil )
	then
		local i,j = string.find(errorString,"gone away")
		if( i >= 0 )
		then
			conn:close()
			log.print(log.warning,"mysql is gone away,now reconnect......\n");
			conn , errMysql= envMysql:connect(_DBNAME,_DBUSER,_DBPWD,_DBADDR,_DBPORT);

			if( errMysql ~=nil)
			then 
				errorString = errMysql
			else
				cursor,errorString  = conn:execute(   sql      )
			end

		end

	end

	mtx.unlock(_mtxDb)
	return cursor,errorString
end




---�������������豸Ϊ����״̬
local sql = [[TRUNCATE TABLE sessions ]]
exesql(conn,sql)










------------------------------------------------------
-----��ȡ�ͻ����豸id,�п�������Ƶ��id��һ��
------------------------------------------------------
function GetDevId(ctx)
	return string.upper(stream.getstream(ctx))
---- return stream.getdevid(ctx)
end


------------------------------------------------------
-----��Ƶ����ǰ��Ȩ
------------------------------------------------------
function OnStreamWillPublish( ctx )
	
return 0 ;
end

------------------------------------------------------
-----��Ƶ����֪ͨ
------------------------------------------------------
function OnStreamPublished( ctx )

	local  devid=GetDevId(ctx)

	---�������ݿ������б�
	local sql = string.format("INSERT INTO sessions(nID,sID,sNameSession) VALUES(%d, '%s','%s') ",stream.getcid(ctx),devid, stream.getstream(ctx))

	local cursor,errorString = exesql( conn,   sql      )
	if( errorString ~=nil)
	then 
		log.print(log.error,errorString.."\n"..sql.."\n");
	return -1
	end

	return 0 ;
end

------------------------------------------------------
-----��Ƶ����֪ͨ
------------------------------------------------------
function OnStreamPublishClosed( ctx )
	local  devid=GetDevId(ctx)

	
	---�������ݿ������б�
	local sql = string.format("delete  from sessions where nID=%d and sID='%s'", stream.getcid(ctx),devid );

	local cursor,errorString = exesql( conn,   sql      )
	if( errorString ~=nil)
	then 
		log.print(log.error,errorString.."\n"..sql.."\n");
	return -1
	end

	return 0 ;
end

------------------------------------------------------
-----����ǰ֪ͨ
------------------------------------------------------
function OnStreamWillPlay(  ctx )
	return 0 ;
end

------------------------------------------------------
-----���ź�֪ͨ
------------------------------------------------------
function OnStreamPlayStarted(  ctx )
	return 0 ;
end
------------------------------------------------------
-----���Źر�֪ͨ
------------------------------------------------------
function OnStreamPlayClosed( ctx )
	return 0 ;
end













