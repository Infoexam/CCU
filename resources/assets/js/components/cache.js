export default {
  setItem (key, value, storage = 'local') {
    const s = 'local' === storage ? localStorage : sessionStorage

    s.setItem(this.getKey(key), JSON.stringify(value))
  },

  removeItem (key) {
    localStorage.removeItem(this.getKey(key))
    sessionStorage.removeItem(this.getKey(key))
  },

  getItem (key, _default = null) {
    let result = localStorage.getItem(this.getKey(key)) || sessionStorage.getItem(this.getKey(key))

    if (null !== result) {
      return JSON.parse(result)
    }

    if ('function' !== typeof _default) {
      return _default
    }

    result = _default()

    return 'function' === typeof result.then ? {} : result
  },

  getKey (key) {
    return `cache-service.${key}`
  }
}
