<nav class="admin-nav indigo">
    <ul class="left">
        <li><a v-link="{name: 'home'}"><i class="material-icons logo-icons left">settings</i><span>後台首頁</span></a></li>
        <li><a></a></li>
    </ul>
</nav>

<ul class="sidebar collection">
    <li class="sidebar-menu collection-item no-padding">
        <div class="collection with-header">
            <a class="sidebar-head collection-header dismissable grey-text text-darken-2">{{ trans('navigation.account./') }}</a>
            <a class="sidebar-item collection-item" v-link="{name: 'exam.sets.index'}">{{ trans('navigation.account.info') }}</a>
            <a class="sidebar-item collection-item" v-link="{name: 'exam.sets.index'}">{{ trans('navigation.account.sync') }}</a>
        </div>
    </li>
    <li class="sidebar-menu collection-item no-padding">
        <div class="collection with-header">
            <a class="sidebar-head collection-header dismissable grey-text text-darken-2">{{ trans('navigation.testing./') }}</a>
            <a class="sidebar-item collection-item" v-link="{name: 'exam.lists.index'}">{{ trans('navigation.testing.list') }}</a>
            <a class="sidebar-item collection-item" v-link="{name: 'exam.sets.index'}">{{ trans('navigation.testing.grade') }}</a>
        </div>
    </li>
    <li class="sidebar-menu collection-item no-padding">
        <div class="collection with-header">
            <a class="sidebar-head collection-header dismissable grey-text text-darken-2">{{ trans('navigation.exam./') }}</a>
            <a class="sidebar-item collection-item" v-link="{name: 'exam.sets.index'}">{{ trans('navigation.exam.set') }}</a>
            <a class="sidebar-item collection-item" v-link="{name: 'exam.papers.index'}">{{ trans('navigation.exam.paper') }}</a>
        </div>
    </li>
    <li class="sidebar-menu collection-item no-padding">
        <div class="collection with-header">
            <a class="sidebar-head collection-header dismissable grey-text text-darken-2">{{ trans('navigation.website./') }}</a>
            <a class="sidebar-item collection-item" v-link="{name: 'announcements.index'}">{{ trans('navigation.website.announcement') }}</a>
            <a class="sidebar-item collection-item" v-link="{name: 'website-maintenance.index'}">{{ trans('navigation.website.maintenance') }}</a>
            <a class="sidebar-item collection-item" v-link="{name: 'ip-rules.index'}">{{ trans('navigation.website.ip') }}</a>
            <a class="sidebar-item collection-item" v-link="{name: 'exam.sets.index'}">{{ trans('navigation.website.faq') }}</a>
            <a class="sidebar-item collection-item" v-link="{name: 'exam.sets.index'}">{{ trans('navigation.website.log') }}</a>
        </div>
    </li>
</ul>
