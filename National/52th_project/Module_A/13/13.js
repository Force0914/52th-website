let urls = []

// 重新導向
const requestHandler = detail => {
  // console.log(detail)
  for (const url of urls) {
    if (url.old.includes('*')) {
      const re = url.old.replace(/\./g, '\\.').replace(/\*/g, '\\S*').replace(/\//g, '\\/')
      const regex = new RegExp(re)
      if (regex.test(detail.url)) {
        return {
          redirectUrl: url.new
        }
      }
    } else if (url.old === detail.url) {
      return { redirectUrl: url.new }
    }
  }
}

// 開始時先取得儲存的設定
chrome.storage.sync.get('url', data => {
  if (data.url) {
    urls = JSON.parse(data.url)
    // https://developer.mozilla.org/en-US/docs/Mozilla/Add-ons/WebExtensions/API/webRequest/onBeforeRequest
    chrome.webRequest.onBeforeRequest.addListener(
      requestHandler,
      { urls: [] },
      ["blocking"]
    )
  }
})

// 當前台改變設定時
chrome.storage.onChanged.addListener(changes => {
  // 更新設定
  urls = JSON.parse(changes.url.newValue)
  // 重新綁定
  chrome.webRequest.onBeforeRequest.removeListener(requestHandler)
  chrome.webRequest.onBeforeRequest.addListener(
    requestHandler,
    { urls: [] },
    ["blocking"]
  )
  chrome.webRequest.handlerBehaviorChanged()
})