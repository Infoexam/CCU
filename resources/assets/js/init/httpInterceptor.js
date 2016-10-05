import Cookies from 'js-cookie'

export default function (request, next) {
  // https://laravel.com/docs/5.2/routing#csrf-protection
  request.headers['X-XSRF-TOKEN'] = Cookies.get('XSRF-TOKEN')

  next(response => {
    //
  })
}
