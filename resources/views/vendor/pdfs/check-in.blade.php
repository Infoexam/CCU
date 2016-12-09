<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
  </head>
  <body>
    <table>
      <thead>
        <tr>
          <th>姓名</th>
          <th>學號</th>
          <th>簽到</th>
          <th>備註</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($listing->getRelation('applies') as $apply)
          <tr>
            <td>{{ $apply->getRelation('user')->getAttribute('name') }}</td>
            <td>{{ $apply->getRelation('user')->getAttribute('username') }}</td>
            <td></td>
            <td>未繳費</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </body>
</html>
