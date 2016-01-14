<nav class="indigo darken-1">
    <div class="nav-wrapper container indigo darken-1">
        <!-- Logo -->
        <!--<a v-link="{name: 'home'}" class="brand-logo">Logo Icon</a>-->

        <!-- Mobile Menu Icon -->
        <a data-activates="nav-mobile-menu" class="button-collapse cursor-pointer">
            <i class="material-icons">menu</i>
        </a>

        <!-- Desktop Menu -->
        <ul class="right hide-on-med-and-down">
            <li>
                <a class="dropdown-button cursor-pointer" data-beloworigin="true" data-activates="nav-dropdown-account">
                    <span>{{ trans('navigation.account./') }}</span><i class="material-icons right">arrow_drop_down</i>
                </a>
            </li>
            <li>
                <a class="dropdown-button cursor-pointer" data-beloworigin="true" data-activates="nav-dropdown-testing">
                    <span>{{ trans('navigation.testing./') }}</span><i class="material-icons right">arrow_drop_down</i>
                </a>
            </li>
            <li>
                <a class="dropdown-button cursor-pointer" data-beloworigin="true" data-activates="nav-dropdown-exam">
                    <span>{{ trans('navigation.exam./') }}</span><i class="material-icons right">arrow_drop_down</i>
                </a>
            </li>
            <li>
                <a class="dropdown-button cursor-pointer" data-beloworigin="true" data-activates="nav-dropdown-website">
                    <span>{{ trans('navigation.website./') }}</span><i class="material-icons right">arrow_drop_down</i>
                </a>
            </li>

        </ul>
    </div>
</nav>

<ul id="nav-dropdown-account" class="dropdown-content">
    <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.account.info') }}</a></li>
    <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.account.sync') }}</a></li>
</ul>

<ul id="nav-dropdown-testing" class="dropdown-content">
    <li><a v-link="{name: 'exam.lists.index'}">{{ trans('navigation.testing.list') }}</a></li>
    <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.testing.grade') }}</a></li>
</ul>

<ul id="nav-dropdown-exam" class="dropdown-content">
    <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.exam.set') }}</a></li>
    <li><a v-link="{name: 'exam.papers.index'}">{{ trans('navigation.exam.paper') }}</a></li>
</ul>

<ul id="nav-dropdown-website" class="dropdown-content">
    <li><a v-link="{name: 'announcements.index'}">{{ trans('navigation.website.announcement') }}</a></li>
    <li><a v-link="{name: 'website-maintenance.index'}">{{ trans('navigation.website.maintenance') }}</a></li>
    <li><a v-link="{name: 'ip-rules.index'}">{{ trans('navigation.website.ip') }}</a></li>
    <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.website.faq') }}</a></li>
    <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.website.log') }}</a></li>
</ul>