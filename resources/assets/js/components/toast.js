let toast = {
  formRequestFailed(response) {
    switch (response.status) {
      case 422:
        this.unprocessableEntity(response.data.errors)
        break

      default:
        this.failed(response.message)
        break
    }
  },

  unprocessableEntity(errors) {
    let _this = this;

    Object.keys(errors).forEach(function (field) {
      errors[field].forEach(function (error) {
        _this.failed(error)
      })
    })
  },

  success(message, duration) {
    this._toast(message, duration)
  },

  failed(message, duration) {
    this._toast(message, duration, 'red darken-2')
  },

  _toast(message = '', duration = 4000, style = 'green') {
    Materialize.toast(message, duration, style)
  }
}

export default toast
