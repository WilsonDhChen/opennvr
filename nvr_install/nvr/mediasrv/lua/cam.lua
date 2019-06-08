
require("string")
_DBNAME="av4loc"
_DBUSER="root"
_DBPWD="123456"
_DBADDR="127.0.0.1"
_DBPORT=3306
---MySQL
envMysql = luasql.mysql.mysql();
conn , errMysql= envMysql:connect(_DBNAME,_DBUSER,_DBPWD,_DBADDR,_DBPORT);


if( errMysql ~=nil)
then 
	print(errMysql);
	app.initfailed(-1)
	return 
end

---����mysql��ʱ
local sql = [[set interactive_timeout=8640000 ]]

local cursor,errorString = conn:execute(   sql      )
if( errorString ~=nil)
then 
	print(errorString);
	app.initfailed(-1)
	return 
end



---�������������豸Ϊ����״̬
local sql = [[update  devices set nVideoOnline=0 ]]

local cursor,errorString = conn:execute(   sql      )
if( errorString ~=nil)
then 
	print(errorString);
	app.initfailed(-1)
	return 
end




---�������������豸Ϊ����״̬
local sql = [[TRUNCATE TABLE sessions ]]

local cursor,errorString = conn:execute(   sql      )
if( errorString ~=nil)
then 
	print(errorString);
	app.initfailed(-1)
	return 
end





----Ϊudp��Ϣ���ʹ�����
mtxUdp = mtx.create();



----Ϊ���ݿ����������
mtxDb= mtx.create();


-----����udp����socket
udpSoc = rdeu.open("",0);
notifyAddr="127.0.01"
notifyPort=9300
rdeu.sendstr(udpSoc,"event=init;id=0;value=0",notifyAddr,notifyPort)


-------------------------------------------------

function exesql(con,sql)
	mtx.lock(mtxDb)

	if ( con == nil )
	then
		mtx.unlock(mtxDb)
		return nil, "mysql not connected"
	end
	
	local cursor,errorString = con:execute(   sql      )

	if( errorString ~= nil )
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

	mtx.unlock(mtxDb)
	return cursor,errorString
end

------------------------------------------------------
-----����udp��Ϣ֪ͨimsrv
------------------------------------------------------

function NotifyConnections(id,session,cid,app,connections)
	local msg ="event=connections;id="..id..";session="..session..";cid="..cid..";value="..connections

	mtx.lock(mtxUdp)
	rdeu.sendstr(udpSoc,msg,notifyAddr,notifyPort)
	mtx.unlock(mtxUdp)
end

function NotifyStatus(event,id,session,cid,value)
	local msg ="event="..event..";id="..id..";session="..session..";cid="..cid..";value="..value

	mtx.lock(mtxUdp)
	rdeu.sendstr(udpSoc,msg,notifyAddr,notifyPort)
	mtx.unlock(mtxUdp)
end

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
			
	local  devid=GetDevId(ctx)
	---�����Ƶ�Ƿ������ݿ�
	local sql = [[select sID from devices where sID=']]..devid..[[']]

	local cursor,errorString =exesql( conn, sql      )
	if( errorString ~=nil)
	then 
		print(errorString);
		print(sql);
		return -1
	end

	if( cursor:numrows() < 1)
	then 
		print("not find "..devid )
		return -1
	else

		return 0 ;
	end
end

------------------------------------------------------
-----��Ƶ����֪ͨ
------------------------------------------------------
function OnStreamPublished( ctx )

	local  devid=GetDevId(ctx)

	---�������ݿ����߱�־
	local sql = [[update  devices set nVideoOnline=1 ,nVideoCid=]].. stream.getcid(ctx)  ..[[ where sID=']]..devid..[[']]

	local cursor,errorString = exesql( conn, sql      )
	if( errorString ~=nil)
	then 
		print(errorString);
		print(sql);
		return -1
	end
	
	
		---�������ݿ������б�
	local sql = string.format("INSERT INTO sessions(nID,sID,sNameSession) VALUES(%d, '%s','%s') ",stream.getcid(ctx),devid, stream.getstream(ctx))

	local cursor,errorString = exesql( conn, sql      )
	if( errorString ~=nil)
	then 
		print(errorString);
		print(sql);
	return -1
	end

	NotifyStatus("online",devid, stream.getstream(ctx),stream.getcid(ctx),"1")
	return 0 ;
end

------------------------------------------------------
-----��Ƶ����֪ͨ
------------------------------------------------------
function OnStreamPublishClosed( ctx )
	local  devid=GetDevId(ctx)
	---�������ݿ����߱�־
	local sql = [[update  devices set nVideoOnline=0 where sID=']]..devid..[[' and nVideoCid=]] .. stream.getcid(ctx)

	local cursor,errorString = exesql( conn, sql      )
	if( errorString ~=nil)
	then 
		print(errorString);
		print(sql);
	return -1
	end
	
	
	---�������ݿ������б�
	local sql = string.format("delete  from sessions where nID=%d and sID='%s'", stream.getcid(ctx),devid );

	local cursor,errorString = exesql( conn, sql      )
	if( errorString ~=nil)
	then 
		print(errorString);
		print(sql);
	return -1
	end

	NotifyStatus("online",devid, stream.getstream(ctx),stream.getcid(ctx),"0")
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

	NotifyConnections(  string.upper(stream.getsrcdevid(ctx)), stream.getstream(ctx),stream.getsrccid(ctx),stream.getapp(ctx),stream.getconnections(ctx) )
	return 0 ;
end
------------------------------------------------------
-----���Źر�֪ͨ
------------------------------------------------------
function OnStreamPlayClosed( ctx )

	NotifyConnections(  string.upper(stream.getsrcdevid(ctx)), stream.getstream(ctx),stream.getsrccid(ctx),stream.getapp(ctx),stream.getconnections(ctx) )
	return 0 ;
end













