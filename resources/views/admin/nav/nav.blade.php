<nav class="admin-nav indigo">
    <ul class="left">
        <li><a v-link="{name: 'home'}"><i class="material-icons logo-icons left">settings</i><span>後台首頁</span></a></li>
        <li><a></a></li>
    </ul>
</nav>

<ul class="sidebar collection">
    <li class="sidebar-menu collection-item no-padding">
        <div class="collection with-header">
            <a class="sidebar-head collection-header dismissable grey-text text-darken-2" data-i18n="navigation.admin.account"></a>
            <a class="sidebar-item collection-item" v-link="{name: 'exam.sets.index'}" data-i18n="navigation.admin.account-info"></a>
            <a class="sidebar-item collection-item" v-link="{name: 'exam.sets.index'}" data-i18n="navigation.admin.account-sync"></a>
        </div>
    </li>
    <li class="sidebar-menu collection-item no-padding">
        <div class="collection with-header">
            <a class="sidebar-head collection-header dismissable grey-text text-darken-2" data-i18n="navigation.admin.testing"></a>
            <a class="sidebar-item collection-item" v-link="{name: 'exam.lists.index'}" data-i18n="navigation.admin.testing-list"></a>
            <a class="sidebar-item collection-item" v-link="{name: 'exam.sets.index'}" data-i18n="navigation.admin.testing-grade"></a>
        </div>
    </li>
    <li class="sidebar-menu collection-item no-padding">
        <div class="collection with-header">
            <a class="sidebar-head collection-header dismissable grey-text text-darken-2" data-i18n="navigation.admin.exam"></a>
            <a class="sidebar-item collection-item" v-link="{name: 'exam.sets.index'}" data-i18n="navigation.admin.exam-set"></a>
            <a class="sidebar-item collection-item" v-link="{name: 'exam.papers.index'}" data-i18n="navigation.admin.exam-paper"></a>
        </div>
    </li>
    <li class="sidebar-menu collection-item no-padding">
        <div class="collection with-header">
            <a class="sidebar-head collection-header dismissable grey-text text-darken-2" data-i18n="navigation.admin.website"></a>
            <a class="sidebar-item collection-item" v-link="{name: 'announcements.index'}" data-i18n="navigation.admin.website-announcement"></a>
            <a class="sidebar-item collection-item" v-link="{name: 'website-maintenance.index'}" data-i18n="navigation.admin.website-maintenance"></a>
            <a class="sidebar-item collection-item" v-link="{name: 'ip-rules.index'}" data-i18n="navigation.admin.website-ip"></a>
            <a class="sidebar-item collection-item" v-link="{name: 'exam.sets.index'}" data-i18n="navigation.admin.website-faq"></a>
            <a class="sidebar-item collection-item" v-link="{name: 'exam.sets.index'}" data-i18n="navigation.admin.website-log"></a>
        </div>
    </li>
</ul>
