-- Main Fishing System Module
-- This handles the core fishing system functionality

local Players = game:GetService("Players")
local ReplicatedStorage = game:GetService("ReplicatedStorage")
local DataStoreService = game:GetService("DataStoreService")
local RunService = game:GetService("RunService")

local FishingSystem = {}

-- DataStore setup
local playerDataStore = DataStoreService:GetDataStore("PlayerFishingData")

-- Player data structure
local defaultPlayerData = {
    Money = 1000,
    Rank = 1,
    Experience = 0,
    Equipment = {
        Rod = "Basic Rod",
        Bait = "Worm",
        RodLevel = 1,
        BaitLevel = 1
    },
    Inventory = {
        Fish = {},
        Bait = {Worm = 10, Bread = 5, Cheese = 3}
    },
    Stats = {
        FishCaught = 0,
        TotalMoneyEarned = 0,
        TimeSpentFishing = 0
    }
}

-- Fish types and their data
local FishData = {
    ["Common Fish"] = {
        Rarity = "Common",
        BasePrice = 10,
        CatchChance = 0.4,
        Experience = 5,
        MinLevel = 1
    },
    ["Rare Fish"] = {
        Rarity = "Rare", 
        BasePrice = 50,
        CatchChance = 0.2,
        Experience = 15,
        MinLevel = 3
    },
    ["Epic Fish"] = {
        Rarity = "Epic",
        BasePrice = 150,
        CatchChance = 0.1,
        Experience = 30,
        MinLevel = 5
    },
    ["Legendary Fish"] = {
        Rarity = "Legendary",
        BasePrice = 500,
        CatchChance = 0.05,
        Experience = 60,
        MinLevel = 8
    }
}

-- Equipment data
local EquipmentData = {
    Rods = {
        ["Basic Rod"] = {
            Level = 1,
            Price = 0,
            CatchBonus = 0,
            Durability = 100
        },
        ["Wooden Rod"] = {
            Level = 2,
            Price = 500,
            CatchBonus = 0.1,
            Durability = 150
        },
        ["Steel Rod"] = {
            Level = 3,
            Price = 1500,
            CatchBonus = 0.2,
            Durability = 200
        },
        ["Golden Rod"] = {
            Level = 4,
            Price = 5000,
            CatchBonus = 0.3,
            Durability = 300
        }
    },
    Bait = {
        ["Worm"] = {
            Level = 1,
            Price = 5,
            CatchBonus = 0.05,
            Quantity = 1
        },
        ["Bread"] = {
            Level = 2,
            Price = 15,
            CatchBonus = 0.1,
            Quantity = 1
        },
        ["Cheese"] = {
            Level = 3,
            Price = 30,
            CatchBonus = 0.15,
            Quantity = 1
        },
        ["Premium Bait"] = {
            Level = 4,
            Price = 100,
            CatchBonus = 0.25,
            Quantity = 1
        }
    }
}

-- Rank system
local RankData = {
    {Level = 1, Name = "Novice Fisher", RequiredExp = 0, Color = Color3.fromRGB(139, 139, 139)},
    {Level = 2, Name = "Apprentice Fisher", RequiredExp = 100, Color = Color3.fromRGB(34, 139, 34)},
    {Level = 3, Name = "Skilled Fisher", RequiredExp = 300, Color = Color3.fromRGB(0, 191, 255)},
    {Level = 4, Name = "Expert Fisher", RequiredExp = 600, Color = Color3.fromRGB(138, 43, 226)},
    {Level = 5, Name = "Master Fisher", RequiredExp = 1000, Color = Color3.fromRGB(255, 215, 0)},
    {Level = 6, Name = "Legendary Fisher", RequiredExp = 1500, Color = Color3.fromRGB(255, 69, 0)},
    {Level = 7, Name = "Fishing God", RequiredExp = 2500, Color = Color3.fromRGB(255, 0, 0)}
}

-- Player data management
local playerData = {}

function FishingSystem:GetPlayerData(player)
    return playerData[player.UserId] or defaultPlayerData
end

function FishingSystem:SavePlayerData(player)
    local data = playerData[player.UserId]
    if data then
        local success, error = pcall(function()
            playerDataStore:SetAsync(player.UserId, data)
        end)
        if not success then
            warn("Failed to save data for " .. player.Name .. ": " .. tostring(error))
        end
    end
end

function FishingSystem:LoadPlayerData(player)
    local success, data = pcall(function()
        return playerDataStore:GetAsync(player.UserId)
    end)
    
    if success and data then
        playerData[player.UserId] = data
    else
        playerData[player.UserId] = defaultPlayerData
    end
    
    return playerData[player.UserId]
end

function FishingSystem:AddMoney(player, amount)
    local data = self:GetPlayerData(player)
    data.Money = data.Money + amount
    data.Stats.TotalMoneyEarned = data.Stats.TotalMoneyEarned + amount
    self:UpdatePlayerUI(player)
    
    -- Fire money change event to client
    local moneyChangeEvent = ReplicatedStorage:FindFirstChild("MoneyChangeEvent")
    if moneyChangeEvent then
        moneyChangeEvent:FireClient(player, amount, true)
    end
end

function FishingSystem:RemoveMoney(player, amount)
    local data = self:GetPlayerData(player)
    if data.Money >= amount then
        data.Money = data.Money - amount
        
        -- Fire money change event to client
        local moneyChangeEvent = ReplicatedStorage:FindFirstChild("MoneyChangeEvent")
        if moneyChangeEvent then
            moneyChangeEvent:FireClient(player, -amount, false)
        end
        
        return true
    end
    return false
end

function FishingSystem:AddExperience(player, amount)
    local data = self:GetPlayerData(player)
    data.Experience = data.Experience + amount
    
    -- Check for rank up
    local newRank = self:CalculateRank(data.Experience)
    if newRank > data.Rank then
        data.Rank = newRank
        self:OnPlayerRankUp(player, newRank)
    end
    
    self:UpdatePlayerUI(player)
end

function FishingSystem:CalculateRank(experience)
    for i = #RankData, 1, -1 do
        if experience >= RankData[i].RequiredExp then
            return RankData[i].Level
        end
    end
    return 1
end

function FishingSystem:OnPlayerRankUp(player, newRank)
    local rankInfo = RankData[newRank]
    if rankInfo then
        -- Fire rank up event to client
        local remoteEvent = ReplicatedStorage:FindFirstChild("RankUpEvent")
        if remoteEvent then
            remoteEvent:FireClient(player, rankInfo)
        end
    end
end

function FishingSystem:TryCatchFish(player)
    local data = self:GetPlayerData(player)
    local rodData = EquipmentData.Rods[data.Equipment.Rod]
    local baitData = EquipmentData.Bait[data.Equipment.Bait]
    
    if not rodData or not baitData then
        return false, "Invalid equipment"
    end
    
    -- Check if player has bait
    if data.Inventory.Bait[data.Equipment.Bait] <= 0 then
        return false, "No bait available"
    end
    
    -- Calculate catch chance
    local baseChance = 0.3
    local rodBonus = rodData.CatchBonus
    local baitBonus = baitData.CatchBonus
    local totalChance = baseChance + rodBonus + baitBonus
    
    if math.random() <= totalChance then
        -- Successfully caught a fish
        local fishType = self:SelectRandomFish(data.Rank)
        local fishData = FishData[fishType]
        
        -- Add fish to inventory
        if not data.Inventory.Fish[fishType] then
            data.Inventory.Fish[fishType] = 0
        end
        data.Inventory.Fish[fishType] = data.Inventory.Fish[fishType] + 1
        
        -- Consume bait
        data.Inventory.Bait[data.Equipment.Bait] = data.Inventory.Bait[data.Equipment.Bait] - 1
        
        -- Add experience
        self:AddExperience(player, fishData.Experience)
        
        -- Update stats
        data.Stats.FishCaught = data.Stats.FishCaught + 1
        
        self:UpdatePlayerUI(player)
        return true, fishType, fishData
    else
        -- Failed to catch fish, still consume bait
        data.Inventory.Bait[data.Equipment.Bait] = data.Inventory.Bait[data.Equipment.Bait] - 1
        self:UpdatePlayerUI(player)
        return false, "No fish caught"
    end
end

function FishingSystem:SelectRandomFish(playerRank)
    local availableFish = {}
    
    for fishType, fishData in pairs(FishData) do
        if playerRank >= fishData.MinLevel then
            table.insert(availableFish, {Type = fishType, Data = fishData})
        end
    end
    
    if #availableFish == 0 then
        return "Common Fish"
    end
    
    -- Weighted random selection based on rarity
    local totalWeight = 0
    for _, fish in pairs(availableFish) do
        totalWeight = totalWeight + fish.Data.CatchChance
    end
    
    local randomValue = math.random() * totalWeight
    local currentWeight = 0
    
    for _, fish in pairs(availableFish) do
        currentWeight = currentWeight + fish.Data.CatchChance
        if randomValue <= currentWeight then
            return fish.Type
        end
    end
    
    return availableFish[1].Type
end

function FishingSystem:SellFish(player, fishType, quantity)
    local data = self:GetPlayerData(player)
    local fishData = FishData[fishType]
    
    if not fishData or not data.Inventory.Fish[fishType] or data.Inventory.Fish[fishType] < quantity then
        return false, "Not enough fish to sell"
    end
    
    local totalPrice = fishData.BasePrice * quantity
    data.Inventory.Fish[fishType] = data.Inventory.Fish[fishType] - quantity
    self:AddMoney(player, totalPrice)
    
    return true, totalPrice
end

function FishingSystem:BuyEquipment(player, equipmentType, equipmentName)
    local data = self:GetPlayerData(player)
    local equipment = EquipmentData[equipmentType][equipmentName]
    
    if not equipment then
        return false, "Equipment not found"
    end
    
    if data.Money < equipment.Price then
        return false, "Not enough money"
    end
    
    if equipmentType == "Rods" then
        data.Equipment.Rod = equipmentName
        data.Equipment.RodLevel = equipment.Level
    elseif equipmentType == "Bait" then
        if not data.Inventory.Bait[equipmentName] then
            data.Inventory.Bait[equipmentName] = 0
        end
        data.Inventory.Bait[equipmentName] = data.Inventory.Bait[equipmentName] + equipment.Quantity
    end
    
    self:RemoveMoney(player, equipment.Price)
    self:UpdatePlayerUI(player)
    return true, "Purchase successful"
end

function FishingSystem:GiftMoney(fromPlayer, toPlayerName, amount)
    local fromData = self:GetPlayerData(fromPlayer)
    
    -- Check if player has enough money
    if fromData.Money < amount then
        return false, "Not enough money"
    end
    
    -- Find target player
    local targetPlayer = nil
    for _, player in pairs(Players:GetPlayers()) do
        if player.Name:lower() == toPlayerName:lower() then
            targetPlayer = player
            break
        end
    end
    
    if not targetPlayer then
        return false, "Player not found"
    end
    
    if targetPlayer == fromPlayer then
        return false, "Cannot gift money to yourself"
    end
    
    -- Transfer money
    local success = self:RemoveMoney(fromPlayer, amount)
    if success then
        self:AddMoney(targetPlayer, amount)
        
        -- Fire gift received event to target player
        local giftReceivedEvent = ReplicatedStorage:FindFirstChild("GiftReceivedEvent")
        if giftReceivedEvent then
            giftReceivedEvent:FireClient(targetPlayer, fromPlayer.Name, amount)
        end
        
        return true, "Gift sent successfully"
    end
    
    return false, "Failed to send gift"
end

function FishingSystem:UpdatePlayerUI(player)
    local data = self:GetPlayerData(player)
    local remoteEvent = ReplicatedStorage:FindFirstChild("UpdateUIEvent")
    if remoteEvent then
        remoteEvent:FireClient(player, data)
    end
end

-- Initialize player when they join
function FishingSystem:InitializePlayer(player)
    self:LoadPlayerData(player)
    self:UpdatePlayerUI(player)
end

-- Clean up when player leaves
function FishingSystem:CleanupPlayer(player)
    self:SavePlayerData(player)
    playerData[player.UserId] = nil
end

-- Auto-save every 5 minutes
spawn(function()
    while true do
        wait(300) -- 5 minutes
        for _, player in pairs(Players:GetPlayers()) do
            self:SavePlayerData(player)
        end
    end
end)

return FishingSystem