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

          <li class="bold">
            <a
              class="collapsible-header active waves-effect waves-teal"
            ><i class="fa fa-file-text fa-fw" aria-hidden="true"></i>試卷</a>

            <div class="collapsible-body">
              <ul>
                <li v-link-active><a v-link="{ name: 'admin.papers', exact: true }">試卷列表</a></li>
                <li v-link-active><a v-link="{ name: 'admin.papers.create', exact: true }">新增試卷</a></li>
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
