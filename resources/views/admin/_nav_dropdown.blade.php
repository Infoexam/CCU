<ul id="nav-dropdown-account-{{ $device }}" class="dropdown-content">
    <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.account.info') }}</a></li>
    <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.account.sync') }}</a></li>
</ul>

<ul id="nav-dropdown-testing-{{ $device }}" class="dropdown-content">
    <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.testing.list') }}</a></li>
    <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.testing.grade') }}</a></li>
</ul>

<ul id="nav-dropdown-exam-{{ $device }}" class="dropdown-content">
    <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.exam.set') }}</a></li>
    <li><a v-link="{name: 'exam.papers.index'}">{{ trans('navigation.exam.paper') }}</a></li>
</ul>

<ul id="nav-dropdown-website-{{ $device }}" class="dropdown-content">
    <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.website.announcement') }}</a></li>
    <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.website.maintenance') }}</a></li>
    <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.website.ip') }}</a></li>
    <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.website.faq') }}</a></li>
    <li><a v-link="{name: 'exam.sets.index'}">{{ trans('navigation.website.log') }}</a></li>
</ul>
