<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <style type="text/css" media="all">
      table {
        width: 100%;
        border:1px solid black;
        border-collapse: collapse;
        text-align: center;
      }

      thead, tbody, tr, th, td {
        border:1px solid black;
      }
    </style>
  </head>
  <body>
    <table class="bordered">
      <tbody>
        <tr>
          <td>場次：{{ $listing->getAttribute('code') }}</td>
          <td>地點：{{ $listing->getAttribute('room') }}</td>
          <td>人數：{{ $listing->getAttribute('applied_num') }}</td>
        </tr>
        <tr>
          <td>類型：{{ trans('infoexam.exam.subject.'.$listing->getRelation('subject')->getAttribute('name')) }}</td>
          <td colspan="2">{{ trans('infoexam.exam.apply.'.$listing->getRelation('applyType')->getAttribute('name')) }}</td>
        </tr>
        <tr>
          <td>時間：{{ $listing->getAttribute('began_at') }}</td>
          <td colspan="2">時長：{{ $listing->getAttribute('duration') }} 分鐘</td>
        </tr>
      </tbody>
    </table>

    <table>
      <thead style="display: table-header-group">
        <tr>
          <th>#</th>
          <th>學號</th>
          <th>姓名</th>
          <th>簽到</th>
          <th>備註</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($listing->getRelation('applies') as $index => $apply)
          <tr style="page-break-inside: avoid">
            <td>{{ $loop->iteration }}</td>
            <td>{{ $apply->getRelation('user')->getAttribute('username') }}</td>
            <td>{{ $apply->getRelation('user')->getAttribute('name') }}</td>
            <td></td>
            <td>
              @if (is_null($apply->getAttribute('paid_at')))
                <span>未繳費</span>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </body>
</html>
