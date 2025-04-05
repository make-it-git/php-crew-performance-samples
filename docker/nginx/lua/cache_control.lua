-- cache_control.lua - Enhances caching for API endpoints

local _M = {}

-- Function to check if a URL should be cached
function _M.should_cache(uri)
    -- Cache only string-data endpoints
    return string.match(uri, "^/api/string.data/%d+$") ~= nil
end

-- Function to generate cache key
function _M.get_cache_key(uri)
    -- Extract ID from URL for use in cache key
    local id = string.match(uri, "^/api/string.data/(%d)+$")
    if id then
        return "api:string-data:" .. id
    end
    return uri
end

return _M 