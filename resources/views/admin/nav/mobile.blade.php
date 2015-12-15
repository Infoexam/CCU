<ul id="nav-mobile-menu" class="side-nav">
    <li class="no-padding">
        <ul class="collapsible collapsible-accordion" data-collapsible="accordion">
            <li class="bold">
                <a class="collapsible-header  waves-effect waves-teal">{{ trans('navigation.account./') }}</a>
                <div class="collapsible-body">
                    <ul>
                        <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.account.info') }}</a></li>
                        <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.account.sync') }}</a></li>
                    </ul>
                </div>
            </li>
            <li class="bold">
                <a class="collapsible-header  waves-effect waves-teal">{{ trans('navigation.testing./') }}</a>
                <div class="collapsible-body">
                    <ul>
                        <li><a v-link="{name: 'exam.lists.index'}">{{ trans('navigation.testing.list') }}</a></li>
                        <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.testing.grade') }}</a></li>
                    </ul>
                </div>
            </li>
            <li class="bold">
                <a class="collapsible-header  waves-effect waves-teal">{{ trans('navigation.exam./') }}</a>
                <div class="collapsible-body">
                    <ul>
                        <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.exam.set') }}</a></li>
                        <li><a v-link="{name: 'exam.papers.index'}">{{ trans('navigation.exam.paper') }}</a></li>
                    </ul>
                </div>
            </li>
            <li class="bold">
                <a class="collapsible-header  waves-effect waves-teal">{{ trans('navigation.website./') }}</a>
                <div class="collapsible-body">
                    <ul>
                        <li><a v-link="{name: 'announcements.index'}">{{ trans('navigation.website.announcement') }}</a></li>
                        <li><a v-link="{name: 'website-maintenance.index'}">{{ trans('navigation.website.maintenance') }}</a></li>
                        <li><a v-link="{name: 'ip-rules.index'}">{{ trans('navigation.website.ip') }}</a></li>
                        <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.website.faq') }}</a></li>
                        <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.website.log') }}</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </li>
</ul>
