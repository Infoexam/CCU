export default {
  setItem (key, value, storage = 'local') {
    const s = 'local' === storage ? localStorage : sessionStorage

    s.setItem(`cache.${key}`, value)
  },

  removeItem (key, storage = 'local') {
    if ('local' === storage) {
      localStorage.removeItem(key)
    } else if ('session' === storage) {
      sessionStorage.removeItem(key)
    }
  },

  getItem (key, _default = null, jsonParse = false) {
    key = `cache.${key}`

    let result = localStorage.getItem(key) || sessionStorage.getItem(key)

    if (null !== result) {
      return jsonParse ? JSON.parse(result) : result
    }

    if ('function' !== typeof _default) {
      return _default
    } else {
      result = _default()

      return 'function' === typeof result.then ? {} : result
    }
  }
}
