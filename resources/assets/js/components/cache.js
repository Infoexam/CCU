export default {
  setItem (key, value, storage = 'local') {
    const s = 'local' === storage ? localStorage : sessionStorage

    s.setItem(`cache.${key}`, JSON.stringify(value))
  },

  removeItem (key, storage = 'local') {
    if ('local' === storage) {
      localStorage.removeItem(key)
    } else if ('session' === storage) {
      sessionStorage.removeItem(key)
    }
  },

  getItem (key, _default = null) {
    key = `cache.${key}`

    let result = localStorage.getItem(key) || sessionStorage.getItem(key)

    if (null !== result) {
      return JSON.parse(result)
    }

    if ('function' !== typeof _default) {
      return _default
    } else {
      result = _default()

      return 'function' === typeof result.then ? {} : result
    }
  }
}
