<style lang="scss" scoped>
  $side-width: 250px;

  @media only screen and (min-width: 993px) {
    header {
      padding-left: $side-width;
    }
  }

  .side-nav {
    width: $side-width;

    .collapsible-body li.active {
      background-color: #42a5f5;
    }

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

      <li class="no-padding">
        <ul class="collapsible" data-collapsible="expandable">
          <li v-for="group in navbar" class="bold">
            <a
              class="collapsible-header active waves-effect waves-teal"
            ><i :class="[group.icon]" class="fa fa-fw" aria-hidden="true"></i>{{ group.name }}</a>

            <div class="collapsible-body">
              <ul>
                <li v-for="link in group.links" v-link-active>
                  <a v-link="link.href">{{ link.name }}</a>
                </li>
              </ul>
            </div>
          </li>
        </ul>
      </li>

      <li class="bold">
        <a
          v-link="{ name: 'home', exact: true }"
          class="waves-effect waves-teal"
        ><i class="fa fa-home fa-fw" aria-hidden="true"></i>前台</a>
      </li>
    </ul>
  </header>
</template>

<script>
  export default {
    data () {
      return {
        navbar: [
          {
            name: '題庫',
            icon: 'fa-tasks',
            links: [
              { href: { name: 'admin.exams', exact: true }, name: '題庫列表' },
              { href: { name: 'admin.exams.create', exact: true }, name: '新增題庫' }
            ]
          },
          {
            name: '試卷',
            icon: 'fa-file-text',
            links: [
              { href: { name: 'admin.papers', exact: true }, name: '試卷列表' },
              { href: { name: 'admin.papers.create', exact: true }, name: '新增試卷' }
            ]
          },
          {
            name: '測驗',
            icon: 'fa-book',
            links: [
              { href: { name: 'admin.listings', exact: true }, name: '測驗列表' },
              { href: { name: 'admin.listings.create', exact: true }, name: '新增測驗' }
            ]
          }
        ]
      }
    },

    ready () {
      $('header .button-collapse').sideNav({
        closeOnClick: 993 > window.innerWidth
      })

      $('header .collapsible').collapsible()
    },

    beforeDestroy () {
      $('.drag-target').remove()
    }
  }
</script>
