<template>
  <footer class="page-footer teal darken-1">
    <div class="container">
      <div class="row">
        <div class="col l6 s12">
          <ul>
            <li>
              <a
                class="dropdown-button grey-text text-lighten-4 cursor-pointer"
                data-activates="translate-dropdown"
              ><i class="material-icons">translate</i> <span>Language</span></a>

              <ul id="translate-dropdown" class="dropdown-content">
                <li v-for="translate in translates">
                  <a @click="changeLocale($key)">{{ translate }}</a>
                </li>
              </ul>
            </li>
          </ul>
        </div>

        <div class="col l4 offset-l2 s12">
          <ul>
            <li v-if="$auth.is('admin')">
              <a
                v-link="{ name: 'admin.exams' }"
                class="grey-text text-lighten-3"
              ><i class="fa fa-dashboard fa-fw" aria-hidden="true"></i>{{ $t('footer.dashboard') }}</a>
            </li>

            <li>
              <a
                v-if="$auth.guest()"
                v-link="{ name: 'signIn' }"
                class="grey-text text-lighten-3"
              ><i class="fa fa-sign-in fa-fw" aria-hidden="true"></i>{{ $t('auth.signIn') }}</a>

              <a
                v-else
                @click="$auth.signOut()"
                class="cursor-pointer grey-text text-lighten-3"
              ><i class="fa fa-sign-out fa-fw" aria-hidden="true"></i>{{ $t('auth.signOut') }}</a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div class="footer-copyright">
      <div class="container">
        <span>© {{ year }} <a v-link="{ name: 'home' }" class="grey-text text-lighten-4">Infoexam</a></span>

        <a
          class="grey-text text-lighten-4 right"
          href="http://www.ccu.edu.tw"
          target="_blank"
        >{{ $t('ccu') }}</a>
      </div>
    </div>
  </footer>
</template>

<script type="text/babel">
  import Cache from '../../components/cache'

  export default {
    data () {
      return {
        translates: {
          zh_TW: '繁體中文',
          en_US: 'English'
        },

        year: new Date().getFullYear()
      }
    },

    methods: {
      changeLocale (locale) {
        Cache.setItem('locale', locale)

        this.$lang.lang = locale
      }
    },

    ready () {
      $('footer a[data-activates="translate-dropdown"]').dropdown()
    }
  }
</script>
