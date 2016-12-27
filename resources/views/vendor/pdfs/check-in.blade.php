<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <style type="text/css" media="all">
      table {
        width: 100%;
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
      }

      thead, tbody, tr, th, td {
        border: 1px solid black;
        page-break-inside: avoid;
      }

      th, td {
        padding: .7rem .5rem;
      }
    </style>
  </head>
  <body>
    @foreach($listing->getRelation('applies')->chunk(40) as $chunks)
      <table style="text-align: left;">
        <tbody>
          <tr>
            <td>場次：{{ $listing->getAttribute('code') }}</td>
            <td>地點：{{ $listing->getAttribute('room') }}</td>
            <td>人數：{{ $listing->getAttribute('applied_num') }} 人</td>
          </tr>
          <tr>
            <td colspan="3">
              類型：{{ trans('infoexam.exam.subject.'.$listing->getRelation('subject')->getAttribute('name')) }}　{{ trans('infoexam.exam.apply.'.$listing->getRelation('applyType')->getAttribute('name')) }}
            </td>
          </tr>
          <tr>
            <td colspan="3">時間：{{ $listing->getAttribute('began_at') }}　{{ $listing->getAttribute('duration') }} 分鐘</td>
          </tr>
        </tbody>
      </table>

      <br>

      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>學號</th>
            <th>姓名</th>
            <th>簽到</th>
            <th>備註</th>
            <th>#</th>
            <th>學號</th>
            <th>姓名</th>
            <th>簽到</th>
            <th>備註</th>
          </tr>
        </thead>
        <tbody>
          @foreach($chunks->chunk(2) as $chunk)
            <tr>
              @foreach ($chunk as $index => $apply)
                <td style="padding: .45rem .3rem;">{{ $index + 1 }}</td>
                <td>{{ $apply->getRelation('user')->getAttribute('username') }}</td>
                <td>{{ $apply->getRelation('user')->getAttribute('name') }}</td>
                <td>　　　　</td>
                <td>
                  @if (is_null($apply->getAttribute('paid_at')))
                    <span>未繳費</span>
                  @endif
                </td>
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
    @endforeach
  </body>
</html>
