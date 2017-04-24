<style lang="scss" scoped>
  $side-width: 250px;

  @media only screen and (min-width: 993px) {
    header {
      padding-left: $side-width;
    }
  }

  .side-nav {
    width: $side-width;

    .bold > a {
      font-weight: bold;
    }

    i.fa {
      margin: 0 !important;
      font-size: inherit !important;
    }
  }
</style>

<template>
  <header>
    <nav class="teal">
      <div class="nav-wrapper container">
        <a class="brand-logo" style="font-size: 1.5rem;">{{ $share.navbar.title.admin }}</a>
        <a href="#" data-activates="navbar-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
      </div>
    </nav>

    <ul id="navbar-mobile" class="side-nav fixed">
      <li class="center">
        <a v-link="{ name: 'admin', exact: true }">
          <!--<object type="image/svg+xml" data="res/materialize.svg">Infoexam</object>-->
          Infoexam
        </a>
      </li>

      <li class="bold" v-for="link in links" v-link-active>
        <a
          v-link="{ name: link.target, exact: true }"
          class="waves-effect waves-teal"
        ><i :class="[link.icon]" class="fa fa-fw" aria-hidden="true"></i>{{ link.name }}</a>
      </li>
    </ul>
  </header>
</template>

<script>
  export default {
    data () {
      return {
        links: [
          {
            name: '訊息',
            icon: 'fa-bullhorn',
            target: 'admin.news'
          },
          {
            name: '帳號',
            icon: 'fa-users',
            target: 'admin.users'
          },
          {
            name: '題庫',
            icon: 'fa-tasks',
            target: 'admin.exams'
          },
          {
            name: '試卷',
            icon: 'fa-file-text',
            target: 'admin.papers'
          },
          {
            name: '測驗',
            icon: 'fa-book',
            target: 'admin.listings'
          },
          {
            name: '成績',
            icon: 'fa-bar-chart',
            target: 'admin.gradings'
          },
          {
            name: '前台',
            icon: 'fa-home',
            target: 'home'
          }
        ]
      }
    },

    ready () {
      $('header .button-collapse').sideNav({
        closeOnClick: 993 > window.innerWidth
      })
    },

    beforeDestroy () {
      $('.drag-target').remove()
    }
  }
</script>
