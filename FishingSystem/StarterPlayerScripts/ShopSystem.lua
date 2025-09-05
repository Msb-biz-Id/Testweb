-- Shop System for Fishing System
-- Handles equipment shop and fish selling

local Players = game:GetService("Players")
local ReplicatedStorage = game:GetService("ReplicatedStorage")
local TweenService = game:GetService("TweenService")

local player = Players.LocalPlayer
local playerGui = player:WaitForChild("PlayerGui")

-- Wait for RemoteEvents
local remoteEventsFolder = ReplicatedStorage:WaitForChild("RemoteEvents")
local buyEquipmentEvent = remoteEventsFolder:WaitForChild("BuyEquipmentEvent")
local sellFishEvent = remoteEventsFolder:WaitForChild("SellFishEvent")
local getPlayerDataFunction = remoteEventsFolder:WaitForChild("GetPlayerDataFunction")
local getShopDataFunction = remoteEventsFolder:WaitForChild("GetShopDataFunction")
local openShopEvent = remoteEventsFolder:WaitForChild("OpenShopEvent")

-- Create shop GUI
local shopGui = Instance.new("ScreenGui")
shopGui.Name = "ShopGui"
shopGui.Parent = playerGui
shopGui.Enabled = false

-- Main shop frame
local shopFrame = Instance.new("Frame")
shopFrame.Name = "ShopFrame"
shopFrame.Size = UDim2.new(0, 600, 0, 500)
shopFrame.Position = UDim2.new(0.5, -300, 0.5, -250)
shopFrame.BackgroundColor3 = Color3.fromRGB(50, 50, 50)
shopFrame.BorderSizePixel = 0
shopFrame.Parent = shopGui

local shopCorner = Instance.new("UICorner")
shopCorner.CornerRadius = UDim.new(0, 15)
shopCorner.Parent = shopFrame

-- Title
local shopTitle = Instance.new("TextLabel")
shopTitle.Name = "ShopTitle"
shopTitle.Size = UDim2.new(1, 0, 0, 50)
shopTitle.Position = UDim2.new(0, 0, 0, 0)
shopTitle.BackgroundColor3 = Color3.fromRGB(70, 70, 70)
shopTitle.BorderSizePixel = 0
shopTitle.Text = "🛒 Fishing Equipment Shop"
shopTitle.TextColor3 = Color3.fromRGB(255, 255, 255)
shopTitle.TextScaled = true
shopTitle.Font = Enum.Font.SourceSansBold
shopTitle.Parent = shopFrame

local titleCorner = Instance.new("UICorner")
titleCorner.CornerRadius = UDim.new(0, 15)
titleCorner.Parent = shopTitle

-- Close button
local closeButton = Instance.new("TextButton")
closeButton.Name = "CloseButton"
closeButton.Size = UDim2.new(0, 40, 0, 40)
closeButton.Position = UDim2.new(1, -50, 0, 5)
closeButton.BackgroundColor3 = Color3.fromRGB(200, 50, 50)
closeButton.BorderSizePixel = 0
closeButton.Text = "✕"
closeButton.TextColor3 = Color3.fromRGB(255, 255, 255)
closeButton.TextScaled = true
closeButton.Font = Enum.Font.SourceSansBold
closeButton.Parent = shopTitle

local closeCorner = Instance.new("UICorner")
closeCorner.CornerRadius = UDim.new(0, 8)
closeCorner.Parent = closeButton

-- Tab buttons
local tabFrame = Instance.new("Frame")
tabFrame.Name = "TabFrame"
tabFrame.Size = UDim2.new(1, -20, 0, 40)
tabFrame.Position = UDim2.new(0, 10, 0, 60)
tabFrame.BackgroundTransparency = 1
tabFrame.Parent = shopFrame

local rodsTab = Instance.new("TextButton")
rodsTab.Name = "RodsTab"
rodsTab.Size = UDim2.new(0.3, 0, 1, 0)
rodsTab.Position = UDim2.new(0, 0, 0, 0)
rodsTab.BackgroundColor3 = Color3.fromRGB(0, 150, 0)
rodsTab.BorderSizePixel = 0
rodsTab.Text = "🎣 Rods"
rodsTab.TextColor3 = Color3.fromRGB(255, 255, 255)
rodsTab.TextScaled = true
rodsTab.Font = Enum.Font.SourceSansBold
rodsTab.Parent = tabFrame

local rodsTabCorner = Instance.new("UICorner")
rodsTabCorner.CornerRadius = UDim.new(0, 8)
rodsTabCorner.Parent = rodsTab

local baitTab = Instance.new("TextButton")
baitTab.Name = "BaitTab"
baitTab.Size = UDim2.new(0.3, 0, 1, 0)
baitTab.Position = UDim2.new(0.35, 0, 0, 0)
baitTab.BackgroundColor3 = Color3.fromRGB(100, 100, 100)
baitTab.BorderSizePixel = 0
baitTab.Text = "🪱 Bait"
baitTab.TextColor3 = Color3.fromRGB(255, 255, 255)
baitTab.TextScaled = true
baitTab.Font = Enum.Font.SourceSansBold
baitTab.Parent = tabFrame

local baitTabCorner = Instance.new("UICorner")
baitTabCorner.CornerRadius = UDim.new(0, 8)
baitTabCorner.Parent = baitTab

local sellTab = Instance.new("TextButton")
sellTab.Name = "SellTab"
sellTab.Size = UDim2.new(0.3, 0, 1, 0)
sellTab.Position = UDim2.new(0.7, 0, 0, 0)
sellTab.BackgroundColor3 = Color3.fromRGB(100, 100, 100)
sellTab.BorderSizePixel = 0
sellTab.Text = "💰 Sell Fish"
sellTab.TextColor3 = Color3.fromRGB(255, 255, 255)
sellTab.TextScaled = true
sellTab.Font = Enum.Font.SourceSansBold
sellTab.Parent = tabFrame

local sellTabCorner = Instance.new("UICorner")
sellTabCorner.CornerRadius = UDim.new(0, 8)
sellTabCorner.Parent = sellTab

-- Content frame
local contentFrame = Instance.new("ScrollingFrame")
contentFrame.Name = "ContentFrame"
contentFrame.Size = UDim2.new(1, -20, 1, -110)
contentFrame.Position = UDim2.new(0, 10, 0, 110)
contentFrame.BackgroundColor3 = Color3.fromRGB(40, 40, 40)
contentFrame.BorderSizePixel = 0
contentFrame.ScrollBarThickness = 8
contentFrame.Parent = shopFrame

local contentCorner = Instance.new("UICorner")
contentCorner.CornerRadius = UDim.new(0, 8)
contentCorner.Parent = contentFrame

-- Current tab
local currentTab = "rods"

-- Tab switching function
local function switchTab(tabName)
    currentTab = tabName
    
    -- Reset all tab colors
    rodsTab.BackgroundColor3 = Color3.fromRGB(100, 100, 100)
    baitTab.BackgroundColor3 = Color3.fromRGB(100, 100, 100)
    sellTab.BackgroundColor3 = Color3.fromRGB(100, 100, 100)
    
    -- Highlight current tab
    if tabName == "rods" then
        rodsTab.BackgroundColor3 = Color3.fromRGB(0, 150, 0)
    elseif tabName == "bait" then
        baitTab.BackgroundColor3 = Color3.fromRGB(0, 150, 0)
    elseif tabName == "sell" then
        sellTab.BackgroundColor3 = Color3.fromRGB(0, 150, 0)
    end
    
    -- Clear content
    for _, child in pairs(contentFrame:GetChildren()) do
        if child:IsA("GuiObject") then
            child:Destroy()
        end
    end
    
    -- Load content based on tab
    if tabName == "rods" then
        loadRodsContent()
    elseif tabName == "bait" then
        loadBaitContent()
    elseif tabName == "sell" then
        loadSellContent()
    end
end

-- Load rods content
function loadRodsContent()
    local shopData = getShopDataFunction:InvokeServer()
    local playerData = getPlayerDataFunction:InvokeServer()
    
    if not shopData or not playerData then return end
    
    local yOffset = 10
    local itemHeight = 80
    
    for rodName, rodData in pairs(shopData.Rods) do
        local itemFrame = Instance.new("Frame")
        itemFrame.Name = rodName .. "Frame"
        itemFrame.Size = UDim2.new(1, -20, 0, itemHeight)
        itemFrame.Position = UDim2.new(0, 10, 0, yOffset)
        itemFrame.BackgroundColor3 = Color3.fromRGB(60, 60, 60)
        itemFrame.BorderSizePixel = 0
        itemFrame.Parent = contentFrame
        
        local itemCorner = Instance.new("UICorner")
        itemCorner.CornerRadius = UDim.new(0, 8)
        itemCorner.Parent = itemFrame
        
        -- Rod name
        local nameLabel = Instance.new("TextLabel")
        nameLabel.Size = UDim2.new(0.6, 0, 0, 25)
        nameLabel.Position = UDim2.new(0, 10, 0, 5)
        nameLabel.BackgroundTransparency = 1
        nameLabel.Text = rodName
        nameLabel.TextColor3 = Color3.fromRGB(255, 255, 255)
        nameLabel.TextScaled = true
        nameLabel.Font = Enum.Font.SourceSansBold
        nameLabel.TextXAlignment = Enum.TextXAlignment.Left
        nameLabel.Parent = itemFrame
        
        -- Price
        local priceLabel = Instance.new("TextLabel")
        priceLabel.Size = UDim2.new(0.3, 0, 0, 25)
        priceLabel.Position = UDim2.new(0.7, 0, 0, 5)
        priceLabel.BackgroundTransparency = 1
        priceLabel.Text = "$" .. rodData.Price
        priceLabel.TextColor3 = Color3.fromRGB(255, 215, 0)
        priceLabel.TextScaled = true
        priceLabel.Font = Enum.Font.SourceSansBold
        priceLabel.TextXAlignment = Enum.TextXAlignment.Right
        priceLabel.Parent = itemFrame
        
        -- Stats
        local statsLabel = Instance.new("TextLabel")
        statsLabel.Size = UDim2.new(0.6, 0, 0, 20)
        statsLabel.Position = UDim2.new(0, 10, 0, 30)
        statsLabel.BackgroundTransparency = 1
        statsLabel.Text = "Level: " .. rodData.Level .. " | Bonus: +" .. (rodData.CatchBonus * 100) .. "%"
        statsLabel.TextColor3 = Color3.fromRGB(200, 200, 200)
        statsLabel.TextScaled = true
        statsLabel.Font = Enum.Font.SourceSans
        statsLabel.TextXAlignment = Enum.TextXAlignment.Left
        statsLabel.Parent = itemFrame
        
        -- Buy button
        local buyButton = Instance.new("TextButton")
        buyButton.Name = "BuyButton"
        buyButton.Size = UDim2.new(0.2, 0, 0, 30)
        buyButton.Position = UDim2.new(0.75, 0, 0, 25)
        buyButton.BackgroundColor3 = Color3.fromRGB(0, 150, 0)
        buyButton.BorderSizePixel = 0
        buyButton.Text = "Buy"
        buyButton.TextColor3 = Color3.fromRGB(255, 255, 255)
        buyButton.TextScaled = true
        buyButton.Font = Enum.Font.SourceSansBold
        buyButton.Parent = itemFrame
        
        local buyCorner = Instance.new("UICorner")
        buyCorner.CornerRadius = UDim.new(0, 6)
        buyCorner.Parent = buyButton
        
        -- Check if already owned or can't afford
        if playerData.Equipment.Rod == rodName then
            buyButton.Text = "Owned"
            buyButton.BackgroundColor3 = Color3.fromRGB(100, 100, 100)
        elseif playerData.Money < rodData.Price then
            buyButton.Text = "No Money"
            buyButton.BackgroundColor3 = Color3.fromRGB(150, 50, 50)
        else
            buyButton.MouseButton1Click:Connect(function()
                buyEquipmentEvent:FireServer("Rods", rodName)
                wait(0.5) -- Wait for server response
                switchTab("rods") -- Refresh
            end)
        end
        
        yOffset = yOffset + itemHeight + 10
    end
    
    contentFrame.CanvasSize = UDim2.new(0, 0, 0, yOffset)
end

-- Load bait content
function loadBaitContent()
    local shopData = getShopDataFunction:InvokeServer()
    local playerData = getPlayerDataFunction:InvokeServer()
    
    if not shopData or not playerData then return end
    
    local yOffset = 10
    local itemHeight = 80
    
    for baitName, baitData in pairs(shopData.Bait) do
        local itemFrame = Instance.new("Frame")
        itemFrame.Name = baitName .. "Frame"
        itemFrame.Size = UDim2.new(1, -20, 0, itemHeight)
        itemFrame.Position = UDim2.new(0, 10, 0, yOffset)
        itemFrame.BackgroundColor3 = Color3.fromRGB(60, 60, 60)
        itemFrame.BorderSizePixel = 0
        itemFrame.Parent = contentFrame
        
        local itemCorner = Instance.new("UICorner")
        itemCorner.CornerRadius = UDim.new(0, 8)
        itemCorner.Parent = itemFrame
        
        -- Bait name
        local nameLabel = Instance.new("TextLabel")
        nameLabel.Size = UDim2.new(0.6, 0, 0, 25)
        nameLabel.Position = UDim2.new(0, 10, 0, 5)
        nameLabel.BackgroundTransparency = 1
        nameLabel.Text = baitName
        nameLabel.TextColor3 = Color3.fromRGB(255, 255, 255)
        nameLabel.TextScaled = true
        nameLabel.Font = Enum.Font.SourceSansBold
        nameLabel.TextXAlignment = Enum.TextXAlignment.Left
        nameLabel.Parent = itemFrame
        
        -- Price
        local priceLabel = Instance.new("TextLabel")
        priceLabel.Size = UDim2.new(0.3, 0, 0, 25)
        priceLabel.Position = UDim2.new(0.7, 0, 0, 5)
        priceLabel.BackgroundTransparency = 1
        priceLabel.Text = "$" .. baitData.Price
        priceLabel.TextColor3 = Color3.fromRGB(255, 215, 0)
        priceLabel.TextScaled = true
        priceLabel.Font = Enum.Font.SourceSansBold
        priceLabel.TextXAlignment = Enum.TextXAlignment.Right
        priceLabel.Parent = itemFrame
        
        -- Stats
        local statsLabel = Instance.new("TextLabel")
        statsLabel.Size = UDim2.new(0.6, 0, 0, 20)
        statsLabel.Position = UDim2.new(0, 10, 0, 30)
        statsLabel.BackgroundTransparency = 1
        statsLabel.Text = "Level: " .. baitData.Level .. " | Bonus: +" .. (baitData.CatchBonus * 100) .. "%"
        statsLabel.TextColor3 = Color3.fromRGB(200, 200, 200)
        statsLabel.TextScaled = true
        statsLabel.Font = Enum.Font.SourceSans
        statsLabel.TextXAlignment = Enum.TextXAlignment.Left
        statsLabel.Parent = itemFrame
        
        -- Current amount
        local amountLabel = Instance.new("TextLabel")
        amountLabel.Size = UDim2.new(0.3, 0, 0, 20)
        amountLabel.Position = UDim2.new(0.4, 0, 0, 30)
        amountLabel.BackgroundTransparency = 1
        amountLabel.Text = "Owned: " .. (playerData.Inventory.Bait[baitName] or 0)
        amountLabel.TextColor3 = Color3.fromRGB(100, 200, 100)
        amountLabel.TextScaled = true
        amountLabel.Font = Enum.Font.SourceSans
        amountLabel.TextXAlignment = Enum.TextXAlignment.Right
        amountLabel.Parent = itemFrame
        
        -- Buy button
        local buyButton = Instance.new("TextButton")
        buyButton.Name = "BuyButton"
        buyButton.Size = UDim2.new(0.2, 0, 0, 30)
        buyButton.Position = UDim2.new(0.75, 0, 0, 25)
        buyButton.BackgroundColor3 = Color3.fromRGB(0, 150, 0)
        buyButton.BorderSizePixel = 0
        buyButton.Text = "Buy"
        buyButton.TextColor3 = Color3.fromRGB(255, 255, 255)
        buyButton.TextScaled = true
        buyButton.Font = Enum.Font.SourceSansBold
        buyButton.Parent = itemFrame
        
        local buyCorner = Instance.new("UICorner")
        buyCorner.CornerRadius = UDim.new(0, 6)
        buyCorner.Parent = buyButton
        
        if playerData.Money < baitData.Price then
            buyButton.Text = "No Money"
            buyButton.BackgroundColor3 = Color3.fromRGB(150, 50, 50)
        else
            buyButton.MouseButton1Click:Connect(function()
                buyEquipmentEvent:FireServer("Bait", baitName)
                wait(0.5) -- Wait for server response
                switchTab("bait") -- Refresh
            end)
        end
        
        yOffset = yOffset + itemHeight + 10
    end
    
    contentFrame.CanvasSize = UDim2.new(0, 0, 0, yOffset)
end

-- Load sell content
function loadSellContent()
    local playerData = getPlayerDataFunction:InvokeServer()
    local shopData = getShopDataFunction:InvokeServer()
    
    if not playerData or not shopData then return end
    
    local yOffset = 10
    local itemHeight = 80
    
    for fishName, fishData in pairs(shopData.Fish) do
        local fishCount = playerData.Inventory.Fish[fishName] or 0
        if fishCount > 0 then
            local itemFrame = Instance.new("Frame")
            itemFrame.Name = fishName .. "Frame"
            itemFrame.Size = UDim2.new(1, -20, 0, itemHeight)
            itemFrame.Position = UDim2.new(0, 10, 0, yOffset)
            itemFrame.BackgroundColor3 = Color3.fromRGB(60, 60, 60)
            itemFrame.BorderSizePixel = 0
            itemFrame.Parent = contentFrame
            
            local itemCorner = Instance.new("UICorner")
            itemCorner.CornerRadius = UDim.new(0, 8)
            itemCorner.Parent = itemFrame
            
            -- Fish name
            local nameLabel = Instance.new("TextLabel")
            nameLabel.Size = UDim2.new(0.4, 0, 0, 25)
            nameLabel.Position = UDim2.new(0, 10, 0, 5)
            nameLabel.BackgroundTransparency = 1
            nameLabel.Text = fishName
            nameLabel.TextColor3 = Color3.fromRGB(255, 255, 255)
            nameLabel.TextScaled = true
            nameLabel.Font = Enum.Font.SourceSansBold
            nameLabel.TextXAlignment = Enum.TextXAlignment.Left
            nameLabel.Parent = itemFrame
            
            -- Quantity
            local quantityLabel = Instance.new("TextLabel")
            quantityLabel.Size = UDim2.new(0.2, 0, 0, 25)
            quantityLabel.Position = UDim2.new(0.4, 0, 0, 5)
            quantityLabel.BackgroundTransparency = 1
            quantityLabel.Text = "x" .. fishCount
            quantityLabel.TextColor3 = Color3.fromRGB(100, 200, 100)
            quantityLabel.TextScaled = true
            quantityLabel.Font = Enum.Font.SourceSansBold
            quantityLabel.TextXAlignment = Enum.TextXAlignment.Center
            quantityLabel.Parent = itemFrame
            
            -- Price per fish
            local priceLabel = Instance.new("TextLabel")
            priceLabel.Size = UDim2.new(0.2, 0, 0, 25)
            priceLabel.Position = UDim2.new(0.6, 0, 0, 5)
            priceLabel.BackgroundTransparency = 1
            priceLabel.Text = "$" .. fishData.BasePrice .. " each"
            priceLabel.TextColor3 = Color3.fromRGB(255, 215, 0)
            priceLabel.TextScaled = true
            priceLabel.Font = Enum.Font.SourceSansBold
            priceLabel.TextXAlignment = Enum.TextXAlignment.Center
            priceLabel.Parent = itemFrame
            
            -- Total value
            local totalLabel = Instance.new("TextLabel")
            totalLabel.Size = UDim2.new(0.2, 0, 0, 25)
            totalLabel.Position = UDim2.new(0.8, 0, 0, 5)
            totalLabel.BackgroundTransparency = 1
            totalLabel.Text = "$" .. (fishData.BasePrice * fishCount)
            totalLabel.TextColor3 = Color3.fromRGB(0, 255, 0)
            totalLabel.TextScaled = true
            totalLabel.Font = Enum.Font.SourceSansBold
            totalLabel.TextXAlignment = Enum.TextXAlignment.Right
            totalLabel.Parent = itemFrame
            
            -- Rarity
            local rarityLabel = Instance.new("TextLabel")
            rarityLabel.Size = UDim2.new(0.4, 0, 0, 20)
            rarityLabel.Position = UDim2.new(0, 10, 0, 30)
            rarityLabel.BackgroundTransparency = 1
            rarityLabel.Text = "Rarity: " .. fishData.Rarity
            rarityLabel.TextColor3 = getRarityColor(fishData.Rarity)
            rarityLabel.TextScaled = true
            rarityLabel.Font = Enum.Font.SourceSans
            rarityLabel.TextXAlignment = Enum.TextXAlignment.Left
            rarityLabel.Parent = itemFrame
            
            -- Sell all button
            local sellAllButton = Instance.new("TextButton")
            sellAllButton.Name = "SellAllButton"
            sellAllButton.Size = UDim2.new(0.15, 0, 0, 30)
            sellAllButton.Position = UDim2.new(0.6, 0, 0, 25)
            sellAllButton.BackgroundColor3 = Color3.fromRGB(0, 150, 0)
            sellAllButton.BorderSizePixel = 0
            sellAllButton.Text = "Sell All"
            sellAllButton.TextColor3 = Color3.fromRGB(255, 255, 255)
            sellAllButton.TextScaled = true
            sellAllButton.Font = Enum.Font.SourceSansBold
            sellAllButton.Parent = itemFrame
            
            local sellAllCorner = Instance.new("UICorner")
            sellAllCorner.CornerRadius = UDim.new(0, 6)
            sellAllCorner.Parent = sellAllButton
            
            sellAllButton.MouseButton1Click:Connect(function()
                sellFishEvent:FireServer(fishName, fishCount)
                wait(0.5) -- Wait for server response
                switchTab("sell") -- Refresh
            end)
            
            -- Sell one button
            local sellOneButton = Instance.new("TextButton")
            sellOneButton.Name = "SellOneButton"
            sellOneButton.Size = UDim2.new(0.15, 0, 0, 30)
            sellOneButton.Position = UDim2.new(0.8, 0, 0, 25)
            sellOneButton.BackgroundColor3 = Color3.fromRGB(0, 100, 200)
            sellOneButton.BorderSizePixel = 0
            sellOneButton.Text = "Sell 1"
            sellOneButton.TextColor3 = Color3.fromRGB(255, 255, 255)
            sellOneButton.TextScaled = true
            sellOneButton.Font = Enum.Font.SourceSansBold
            sellOneButton.Parent = itemFrame
            
            local sellOneCorner = Instance.new("UICorner")
            sellOneCorner.CornerRadius = UDim.new(0, 6)
            sellOneCorner.Parent = sellOneButton
            
            sellOneButton.MouseButton1Click:Connect(function()
                sellFishEvent:FireServer(fishName, 1)
                wait(0.5) -- Wait for server response
                switchTab("sell") -- Refresh
            end)
            
            yOffset = yOffset + itemHeight + 10
        end
    end
    
    if yOffset == 10 then
        -- No fish to sell
        local noFishLabel = Instance.new("TextLabel")
        noFishLabel.Size = UDim2.new(1, -20, 0, 50)
        noFishLabel.Position = UDim2.new(0, 10, 0, 50)
        noFishLabel.BackgroundTransparency = 1
        noFishLabel.Text = "No fish to sell!\nGo catch some fish first."
        noFishLabel.TextColor3 = Color3.fromRGB(200, 200, 200)
        noFishLabel.TextScaled = true
        noFishLabel.Font = Enum.Font.SourceSans
        noFishLabel.Parent = contentFrame
    end
    
    contentFrame.CanvasSize = UDim2.new(0, 0, 0, yOffset)
end

-- Get rarity color
function getRarityColor(rarity)
    if rarity == "Common" then
        return Color3.fromRGB(200, 200, 200)
    elseif rarity == "Rare" then
        return Color3.fromRGB(0, 150, 255)
    elseif rarity == "Epic" then
        return Color3.fromRGB(150, 0, 255)
    elseif rarity == "Legendary" then
        return Color3.fromRGB(255, 150, 0)
    else
        return Color3.fromRGB(255, 255, 255)
    end
end

-- Tab button events
rodsTab.MouseButton1Click:Connect(function()
    switchTab("rods")
end)

baitTab.MouseButton1Click:Connect(function()
    switchTab("bait")
end)

sellTab.MouseButton1Click:Connect(function()
    switchTab("sell")
end)

-- Close button
closeButton.MouseButton1Click:Connect(function()
    shopGui.Enabled = false
end)

-- Open shop event
openShopEvent.OnClientEvent:Connect(function()
    shopGui.Enabled = true
    switchTab("rods") -- Default to rods tab
end)

-- Initialize with rods tab
switchTab("rods")

print("Shop System initialized successfully!")