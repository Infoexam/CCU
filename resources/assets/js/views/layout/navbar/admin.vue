<style lang="sass" scoped>
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
      <li class="logo">
        <a id="logo-container" href="http://materializecss.com/" class="brand-logo">
          Logo
          <!--<object type="image/svg+xml" data="res/materialize.svg">Logo</object>-->
        </a>
      </li>

      <li class="no-padding">
        <ul class="collapsible collapsible-accordion">
          <li class="bold">
            <a
              class="collapsible-header active waves-effect waves-teal"
            ><i class="fa fa-tasks fa-fw" aria-hidden="true"></i>題庫</a>

            <div class="collapsible-body">
              <ul>
                <li v-link-active><a v-link="{ name: 'admin.exams', exact: true }">題庫列表</a></li>
                <li v-link-active><a v-link="{ name: 'admin.exams.create', exact: true }">新增題庫</a></li>
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

<script type="text/babel">
  export default {
    ready () {
      $('.button-collapse').sideNav({
        closeOnClick: 993 > window.innerWidth
      })
    },

    beforeDestroy () {
      $('.drag-target').remove()
    }
  }
</script>
