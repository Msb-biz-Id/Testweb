-- Main Server Script for Fishing System
-- Handles all server-side fishing system logic

local Players = game:GetService("Players")
local ReplicatedStorage = game:GetService("ReplicatedStorage")
local ServerScriptService = game:GetService("ServerScriptService")

-- Wait for RemoteEvents to be created
wait(2)

-- Get RemoteEvents
local remoteEventsFolder = ReplicatedStorage:WaitForChild("RemoteEvents")
local tryCatchFishEvent = remoteEventsFolder:WaitForChild("TryCatchFishEvent")
local buyEquipmentEvent = remoteEventsFolder:WaitForChild("BuyEquipmentEvent")
local sellFishEvent = remoteEventsFolder:WaitForChild("SellFishEvent")
local getPlayerDataFunction = remoteEventsFolder:WaitForChild("GetPlayerDataFunction")
local getShopDataFunction = remoteEventsFolder:WaitForChild("GetShopDataFunction")

-- Get MainModule
local MainModule = require(ServerScriptService:WaitForChild("MainModule"))
local fishingSystem = MainModule

-- Player data tracking
local playerFishingStates = {}

-- Handle fishing attempts
tryCatchFishEvent.OnServerEvent:Connect(function(player)
    if not playerFishingStates[player.UserId] then
        playerFishingStates[player.UserId] = {
            IsFishing = false,
            FishingStartTime = 0
        }
    end
    
    local fishingState = playerFishingStates[player.UserId]
    
    if not fishingState.IsFishing then
        -- Start fishing
        fishingState.IsFishing = true
        fishingState.FishingStartTime = tick()
        
        -- Fire fishing started event
        local fishingStartedEvent = remoteEventsFolder:FindFirstChild("FishingStartedEvent")
        if fishingStartedEvent then
            fishingStartedEvent:FireClient(player)
        end
        
        -- Simulate fishing time (3-8 seconds)
        local fishingTime = math.random(3, 8)
        wait(fishingTime)
        
        -- Try to catch fish
        local success, result, fishData = fishingSystem:TryCatchFish(player)
        
        -- Stop fishing
        fishingState.IsFishing = false
        
        -- Fire fishing stopped event
        local fishingStoppedEvent = remoteEventsFolder:FindFirstChild("FishingStoppedEvent")
        if fishingStoppedEvent then
            fishingStoppedEvent:FireClient(player)
        end
        
        -- Fire fish caught event with result
        local fishCaughtEvent = remoteEventsFolder:FindFirstChild("FishCaughtEvent")
        if fishCaughtEvent then
            fishCaughtEvent:FireClient(player, success, result, fishData)
        end
    end
end)

-- Handle equipment purchases
buyEquipmentEvent.OnServerEvent:Connect(function(player, equipmentType, equipmentName)
    local success, message = fishingSystem:BuyEquipment(player, equipmentType, equipmentName)
    
    -- Fire response back to client
    local responseEvent = remoteEventsFolder:FindFirstChild("BuyEquipmentResponseEvent")
    if responseEvent then
        responseEvent:FireClient(player, success, message)
    end
end)

-- Handle fish selling
sellFishEvent.OnServerEvent:Connect(function(player, fishType, quantity)
    local success, result = fishingSystem:SellFish(player, fishType, quantity)
    
    -- Fire response back to client
    local responseEvent = remoteEventsFolder:FindFirstChild("SellFishResponseEvent")
    if responseEvent then
        responseEvent:FireClient(player, success, result)
    end
end)

-- Handle get player data requests
getPlayerDataFunction.OnServerInvoke = function(player)
    return fishingSystem:GetPlayerData(player)
end

-- Handle get shop data requests
getShopDataFunction.OnServerInvoke = function(player)
    local shopData = {
        Rods = fishingSystem.EquipmentData.Rods,
        Bait = fishingSystem.EquipmentData.Bait,
        Fish = fishingSystem.FishData
    }
    return shopData
end

-- Initialize players when they join
Players.PlayerAdded:Connect(function(player)
    wait(1) -- Wait for character to load
    fishingSystem:InitializePlayer(player)
end)

-- Clean up when players leave
Players.PlayerRemoving:Connect(function(player)
    fishingSystem:CleanupPlayer(player)
    playerFishingStates[player.UserId] = nil
end)

-- Auto-save player data every 5 minutes
spawn(function()
    while true do
        wait(300) -- 5 minutes
        for _, player in pairs(Players:GetPlayers()) do
            fishingSystem:SavePlayerData(player)
        end
    end
end)

print("Fishing System Server initialized successfully!")