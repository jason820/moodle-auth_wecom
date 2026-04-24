# Moodle 企業微信認證插件 (auth_wecom)

![Moodle Version](https://img.shields.io/badge/Moodle-5.2+-orange.svg)
![License](https://img.shields.io/badge/License-GPL%20v3-blue.svg)

這是一個專為 Moodle 5.2+ 開發的企業微信（WeCom）認證插件。它實現了 Moodle 系統與企業微信的單點登錄（SSO）深度整合，支援移動端靜默登錄、PC 端掃碼登錄、自動帳號匹配及自動建帳等核心功能。

## 🌟 功能特色

- **多場景 SSO 登錄**：
  - **移動端**：在企業微信內置瀏覽器中自動識別並觸發靜默授權，實現「打開即登錄」。
  - **PC 端**：在 Moodle 登錄頁面自動嵌入企業微信掃碼組件。
- **帳號一致性匹配 (策略 A)**：
  - 自動比對企業微信 `UserId` 與 Moodle `username`（自動處理小寫轉換），實現無感綁定。
- **自動建帳 (Just-In-Time Provisioning)**：
  - 支援從企業微信通訊錄 API 自動獲取用戶姓名、郵箱，並為新用戶自動創建 Moodle 帳號。
- **獨佔性綁定管理**：
  - 確保一個企業微信帳號僅能綁定一個 Moodle 帳號。若重新綁定，系統會自動更新關聯。
  - 用戶可在個人資料頁面查看綁定狀態，並進行手動解綁。
- **多語言支援**：提供完整的繁體中文（zh_tw）、簡體中文（zh_cn）及英文（en）語言包。

## ⚙️ 系統需求

- Moodle 5.2 或更高版本。
- 企業微信管理後台權限（需獲取 `CorpID`、`AgentId` 及 `Secret`）。
- Moodle 伺服器需具備訪問企業微信 API 接口的權限（請務必在企業微信後台設置**可信 IP**）。

## 🚀 安裝指南

1. 下載本倉庫代碼。
2. 將目錄命名為 `wecom` 並放置於 Moodle 根目錄下的 `auth/` 資料夾中：
   `path/to/moodle/auth/wecom`
3. 以管理員身份登錄 Moodle，系統會自動檢測到新插件並引導完成安裝。
4. 前往 **網站管理 > 插件 > 認證 > 管理認證**，啟用「企業微信認證」並將其移動至列表上方。

## 🛠️ 配置步驟

進入插件設置頁面（網站管理 > 插件 > 認證 > 企業微信認證）：

1. **CorpID**：填寫企業微信的企業 ID。
2. **CorpSecret**：填寫該自建應用的 Secret。
3. **AgentId**：填寫該自建應用的 AgentId。
4. **啟用自動建帳**：根據需求開啟或關閉。
5. **可信網域與 IP**：請確保在企業微信後台已正確配置 Moodle 的網域以及伺服器公網 IP。

## 📄 授權協議

本項目採用 [GNU GPL v3](http://www.gnu.org/licenses/gpl.html) 協議開源。
