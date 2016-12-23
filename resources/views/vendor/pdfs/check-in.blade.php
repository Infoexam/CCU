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
    <h4>場次：{{ $listing->getAttribute('code') }}</h4>

    <table>
      <thead style="display: table-header-group">
        <tr>
          <th>姓名</th>
          <th>學號</th>
          <th>簽到</th>
          <th>備註</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($listing->getRelation('applies') as $index => $apply)
          <tr style="page-break-inside: avoid">
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
