import Cookies from 'js-cookie'

let httpQueue = 0

export default function (request, next) {
  // https://laravel.com/docs/5.2/routing#csrf-protection
  request.headers['X-XSRF-TOKEN'] = Cookies.get('XSRF-TOKEN')

  if (0 === httpQueue++) {
    this.$progress.start()
  } else {
    this.$progress.increase(15)
  }

  next(response => {
    if (! response.ok) {
      httpQueue = 0

      this.$progress.failed()
    } else {
      --httpQueue

      if (0 === httpQueue) {
        this.$progress.finish()
      } else if (0 < httpQueue) {
        this.$progress.increase(15)
      }
    }
  })
}
