
redis = require 'redis'

local redispool = {}  
redispool.__index = redispool  
  
function redispool:new(connect_info)  
    local temp = {}  
    setmetatable(temp,redispool) 
    temp.redispoolList = {}  
    temp.mtx = CMtx:new()
    temp.connect_info = connect_info
    return temp  
end  
  
function redispool:clear()  
    self.mtx:lock()
    self.redispoolList = {} 
    self.mtx:unlock()
end  
  
function redispool:pop()  
    self.mtx:lock()
    if #self.redispoolList == 0 then  
        self.mtx:unlock()
        return  redis.connect(self.connect_info)
    end   
      
    local cli = table.remove(self.redispoolList) 
    self.mtx:unlock()
    return  cli  
end  
  
function redispool:push(t) 
    if t == nil then
        return 
    end
	
    if #self.redispoolList > 100 then
        t:quit()
        return 
    end
    self.mtx:lock()
    table.insert(self.redispoolList,t)  
    self.mtx:unlock()
end  
  
function redispool:count()  
    return #self.redispoolList  
end  

return redispool


