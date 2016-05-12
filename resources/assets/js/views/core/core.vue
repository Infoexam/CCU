<template>
  <progress
    :percent.sync="progressConfig.percent"
    :options="progressConfig.options"
  ></progress>

  <header></header>

  <main class="container">
    <router-view></router-view>
  </main>

  <footer class="page-footer">
    <div class="container">
      <div class="row">
        <div class="col l6 s12">
          <h5 class="white-text">Footer Content</h5>

          <p class="grey-text text-lighten-4">More text.</p>
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
  import Events from '../../events'
  import Progress from 'vue-progressbar/vue-progressbar.vue'

  export default {
    data () {
      return {
        progressConfig: {
          percent: 0,

          options: {
            show: true,
            canSuccess: true,
            color: '#29d',
            failedColor: 'red',
            height: '2px'
          }
        }
      }
    },

    events: Events,

    methods: {
      signOutCallback () {
        this.$router.go({ name: 'home' })
      }
    },

    components: {
      progress: Progress
    },

    created () {
      this.$progress.setHolder(this.progressConfig)
    }
  }
</script>
