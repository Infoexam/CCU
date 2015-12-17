<nav class="breadnav transparent hide-on-small-only" v-show="breadcrumbs.length">
    <div class="nav-wrapper">
        <div class="col s12">
            <span class="breadcrumb-navi material-icons blue-text text-darken-4">navigation</span>
            <a v-for="breadcrumb in breadcrumbs" v-link="{name: breadcrumb.name, params: breadcrumb.params}" class="breadcrumb blue-text">@{{ breadcrumb.name }}</a>
        </div>
    </div>
</nav>