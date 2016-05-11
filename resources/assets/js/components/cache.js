export default {
  setItem(key, value, storage = 'local') {
    let s = 'local' === storage ? localStorage : sessionStorage

    s.setItem(`cache.${key}`, value)
  },

  removeItem(key, storage = 'local') {
    if ('local' === storage) {
      localStorage.removeItem(key)
    } else if ('session' === storage) {
      sessionStorage.removeItem(key)
    }
  },

  getItem(key, _default = null) {
    key = `cache.${key}`

    if (null !== localStorage.getItem(key)) {
      return localStorage.getItem(key)
    } else if (null !== sessionStorage.getItem(key)) {
      return sessionStorage.getItem(key)
    }

    return 'function' === typeof _default ? _default() : _default
  }
}
