let toast = {
  success(message, duration) {
    this._toast(message, duration)
  },

  failed(message, duration) {
    this._toast(message, duration, 'red darken-1')
  },

  _toast(message = '', duration = 4000, style = 'green') {
    Materialize.toast(message, duration, style)
  }
}

export default toast
