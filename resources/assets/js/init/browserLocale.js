import Cache from '../components/cache'

const lang = Cache.getItem('locale', () => {
  let _lang

  if (navigator.languages) {
    _lang = navigator.languages[0]
  } else if (navigator.userLanguage) {
    _lang = navigator.userLanguage
  } else {
    _lang = navigator.language
  }

  return _lang
})

export default lang.replace('-', '_')
