export default {
  formRequestFailed (response) {
    switch (response.status) {
      case 422:
        this.unprocessableEntity(response.data.errors)
        break

      default:
        this.failed(response.message)
        break
    }
  },

  unprocessableEntity (errors) {
    for (const key of Object.keys(errors)) {
      for (const msg of errors[key]) {
        this.failed(msg)
      }
    }
  },

  success (message, duration) {
    this.toast(message, duration)
  },

  failed (message, duration) {
    this.toast(message, duration, 'red darken-2')
  },

  toast (message = '', duration = 4000, style = 'green') {
    Materialize.toast(message, duration, style)
  }
}
