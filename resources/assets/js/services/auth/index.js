import Extend from './extend'

function install(Vue, router) {
  Extend(Vue, router)

  Vue.auth.me()
}

if (typeof window !== 'undefined' && window.Vue) {
  window.Vue.use(install)
}

export default install
