-- RemoteEvents for Fishing System
-- This script creates all necessary RemoteEvents and RemoteFunctions

local ReplicatedStorage = game:GetService("ReplicatedStorage")

-- Create RemoteEvents folder if it doesn't exist
local remoteEventsFolder = ReplicatedStorage:FindFirstChild("RemoteEvents")
if not remoteEventsFolder then
    remoteEventsFolder = Instance.new("Folder")
    remoteEventsFolder.Name = "RemoteEvents"
    remoteEventsFolder.Parent = ReplicatedStorage
end

-- Fishing Events
local tryCatchFishEvent = Instance.new("RemoteEvent")
tryCatchFishEvent.Name = "TryCatchFishEvent"
tryCatchFishEvent.Parent = remoteEventsFolder

local fishCaughtEvent = Instance.new("RemoteEvent")
fishCaughtEvent.Name = "FishCaughtEvent"
fishCaughtEvent.Parent = remoteEventsFolder

local fishingStartedEvent = Instance.new("RemoteEvent")
fishingStartedEvent.Name = "FishingStartedEvent"
fishingStartedEvent.Parent = remoteEventsFolder

local fishingStoppedEvent = Instance.new("RemoteEvent")
fishingStoppedEvent.Name = "FishingStoppedEvent"
fishingStoppedEvent.Parent = remoteEventsFolder

-- Shop Events
local buyEquipmentEvent = Instance.new("RemoteEvent")
buyEquipmentEvent.Name = "BuyEquipmentEvent"
buyEquipmentEvent.Parent = remoteEventsFolder

local sellFishEvent = Instance.new("RemoteEvent")
sellFishEvent.Name = "SellFishEvent"
sellFishEvent.Parent = remoteEventsFolder

-- UI Events
local updateUIEvent = Instance.new("RemoteEvent")
updateUIEvent.Name = "UpdateUIEvent"
updateUIEvent.Parent = remoteEventsFolder

local openShopEvent = Instance.new("RemoteEvent")
openShopEvent.Name = "OpenShopEvent"
openShopEvent.Parent = remoteEventsFolder

local openInventoryEvent = Instance.new("RemoteEvent")
openInventoryEvent.Name = "OpenInventoryEvent"
openInventoryEvent.Parent = remoteEventsFolder

-- Rank Events
local rankUpEvent = Instance.new("RemoteEvent")
rankUpEvent.Name = "RankUpEvent"
rankUpEvent.Parent = remoteEventsFolder

-- Gift Events
local giftMoneyEvent = Instance.new("RemoteEvent")
giftMoneyEvent.Name = "GiftMoneyEvent"
giftMoneyEvent.Parent = remoteEventsFolder

local giftReceivedEvent = Instance.new("RemoteEvent")
giftReceivedEvent.Name = "GiftReceivedEvent"
giftReceivedEvent.Parent = remoteEventsFolder

-- Money Events
local moneyChangeEvent = Instance.new("RemoteEvent")
moneyChangeEvent.Name = "MoneyChangeEvent"
moneyChangeEvent.Parent = remoteEventsFolder

local giftMoneyResponseEvent = Instance.new("RemoteEvent")
giftMoneyResponseEvent.Name = "GiftMoneyResponseEvent"
giftMoneyResponseEvent.Parent = remoteEventsFolder

-- Remote Functions
local getPlayerDataFunction = Instance.new("RemoteFunction")
getPlayerDataFunction.Name = "GetPlayerDataFunction"
getPlayerDataFunction.Parent = remoteEventsFolder

local getShopDataFunction = Instance.new("RemoteFunction")
getShopDataFunction.Name = "GetShopDataFunction"
getShopDataFunction.Parent = remoteEventsFolder

print("Fishing System RemoteEvents created successfully!")