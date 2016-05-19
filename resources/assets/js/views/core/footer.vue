<template>
  <footer class="page-footer">
    <div class="container">
      <div class="row">
        <div class="col l6 s12">
          <h5 class="white-text">{{ $t('infoexam') }}</h5>

          <div>
            <a
              class="dropdown-button grey-text text-lighten-4 cursor-pointer"
              data-activates="translate-dropdown"
            ><i class="material-icons">translate</i></a>

            <ul id="translate-dropdown" class="dropdown-content">
              <li v-for="translate in translates">
                <a @click="changeLocale($key)">{{ translate }}</a>
              </li>
            </ul>
          </div>
        </div>

        <div class="col l4 offset-l2 s12">
          <h5 class="white-text">連結</h5>

          <ul>
            <li v-if="$auth.guest()">
              <a
                v-link="{ name: 'signIn' }"
                class="grey-text text-lighten-3"
              >{{ $t('auth.signIn') }}</a>
            </li>

            <li v-else>
              <a
                @click="$auth.signOut(signOutCallback)"
                class="cursor-pointer grey-text text-lighten-3"
              >{{ $t('auth.signOut') }}</a>
            </li>

            <template v-if="$auth.is('admin')">
              <li><a v-link="{ name: 'admin.exams' }" class="grey-text text-lighten-3">後台</a></li>
              <li><a v-link="{ name: 'home' }" class="grey-text text-lighten-3">前台</a></li>
            </template>
          </ul>
        </div>
      </div>
    </div>

    <div class="footer-copyright">
      <div class="container">
        <span>© 2016 <a v-link="{ name: 'home' }" class="grey-text text-lighten-4">Infoexam</a></span>

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
        }
      }
    },

    methods: {
      signOutCallback () {
        this.$router.go({ name: 'home' })
      },

      changeLocale (locale) {
        Cache.setItem('locale', locale)

        this.$lang.lang = locale
      }
    },

    ready () {
      $('a[data-activates="translate-dropdown"]').dropdown();
    }
  }
</script>
