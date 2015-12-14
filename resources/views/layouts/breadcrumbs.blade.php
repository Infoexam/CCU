<nav class="breadnav transparent" v-show="breadcrumbs.length">
    <div class="nav-wrapper">
        <div class="col s12">
            <a v-for="breadcrumb in breadcrumbs" v-link="{name: breadcrumb.name, params: breadcrumb.params}" class="breadcrumb">@{{ breadcrumb.name }}</a>
        </div>
    </div>
</nav>
