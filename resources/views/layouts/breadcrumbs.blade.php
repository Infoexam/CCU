<nav class="breadnav transparent hide-on-small-only" v-show="breadcrumbs.length">
    <div class="nav-wrapper">
        <div class="col s12">
            <span class="breadcrumb-navi material-icons black-text">navigation</span>
            <a v-for="breadcrumb in breadcrumbs" v-link="{name: breadcrumb.name, params: breadcrumb.params}" class="breadcrumb blue-text text-lighten-1">@{{ breadcrumb.name }}</a>
        </div>
    </div>
</nav>