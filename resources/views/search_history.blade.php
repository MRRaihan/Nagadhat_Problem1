<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Search result</title>
    <style>
        table {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        table td, table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #ddd;
        }

        table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
        }
    </style>
</head>
<body>
<h1>User Search History</h1>

<div>
    <h3>Filters</h3>
    <form id="filter-form">
        <label for="keywords">All Keywords:</label>
        @foreach($keywords as $keyword)
            <label>
                <input type="checkbox" name="keywords[]"
                       value="{{ $keyword->keyword }}">{{ $keyword->keyword }} {{--({{ $keywordCounts[$keyword] }} times found)--}}
            </label>
        @endforeach

        <br>
        <br>
        <label for="users">All Users:</label>
        @foreach($users as $user)
            <label>
                <input type="checkbox" name="users[]" value="{{ $user->id }}">{{ $user->name }}
            </label>
        @endforeach
        <br>
        <br>
        <div>
            <label for="time-range">Time Range:</label>
            <label><input type="checkbox" name="yesterday" value="1"> See data from yesterday</label>
            <label><input type="checkbox" name="last-week" value="1"> See data from last week</label>
            <label><input type="checkbox" name="last-month" value="1"> See data from last month</label>
        </div>
        <br>
        <div>
            <label for="date-range">Select Date:</label>
            <input type="date" name="start-date">
            <input type="date" name="end-date">
        </div>

        <br>

        <button type="submit">Apply Filters</button>
    </form>
</div>
<br>
<div id="error-messages" style="color: red; font-size: 17px; font-weight: bold;"></div>
<div>
    <h3>Search Keyword Results</h3>
    <ul id="keyword">
    </ul>
</div>

<div>
    <h3>Search History Results</h3>
    <table style="border-collapse: collapse; border: 1px solid black">
        <thead>
        <tr>
            <th>#SL</th>
            <th>Search Keyword</th>
            <th>User</th>
            <th>Searched At</th>
        </tr>
        </thead>
        <tbody id="search-history-table-container">
        </tbody>
    </table>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        displayNoData();
        keywordNoDisplay();
        $('#filter-form').submit(function (e) {
            e.preventDefault();
            // Collect selected filters and send an AJAX request
            var filters = $('#filter-form').serialize();

            $.ajax({
                url: '/filter-search-history', // Replace with your actual route
                type: 'GET',
                data: filters,
                success: function (data) {
                    const length = data.data.length;
                    if (length > 0) {
                        displaySearchHistory(data.data)
                    } else {
                        displayNoData();
                    }

                    if (Object.keys(data.keywords).length > 0) {
                        displayKeyword(data.keywords)
                    } else {
                        keywordNoDisplay();
                    }
                    $('#error-messages').html('');
                },
                error: function (e) {
                    $('#error-messages').html(`<li>${e.responseJSON.error}</li>`);
                },
            });
        });

        function displayNoData() {
            let tableHtml = `<tr><td colspan="4"> No data found </td></tr>`
            $('#search-history-table-container').html(tableHtml);
        }

        function displaySearchHistory(searchHistory) {
            let tableHtml = '';
            searchHistory.forEach(function (item, index) {
                const {search_keyword, searched_at, user: {name}} = item;
                tableHtml += `<tr>
                                   <td>${(index + 1)}</td>
                                   <td>${search_keyword}</td>
                                   <td>${name}</td>
                                   <td>${searched_at}</td>
                              </td>`;
            });

            $('#search-history-table-container').html(tableHtml);
        }

        function displayKeyword(keywords) {
            let keywordList = Object.keys(keywords).map(item => `<li>${item} => ${keywords[item]} times</li>`)
            $('#keyword').html(keywordList);
        }

        function keywordNoDisplay() {
            $('#keyword').html('<p>No data found</p>');
        }
    });
</script>

</body>
</html>

