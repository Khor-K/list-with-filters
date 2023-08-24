<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Фильтр компаний</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <style>
        #wrapper {
            display: flex;
        }
        #filters {
            margin-left: 20px;
            width: 300px;
        }
        #companyTable {
            width: 600px;
            overflow-y: auto;
            max-height: 300px;
            border-collapse: collapse;
        }
        #companyTable th, #companyTable td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        .selected {
            background-color: #f2f2f2;
        }
        #selectedItems {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    
<div id="selectedItems">Выбранные ID: <span id="selectedList"></span></div>
<div id="wrapper">
    <!-- Таблица с компаниями -->
    <table id="companyTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Company</th>
            </tr>
        </thead>
        <tbody>
            <!-- Заполнение через JS -->
        </tbody>
    </table>
    
    <div id="filters">
        <h3>Фильтры:</h3>
        
        <div class="listcontrol-group-add-row">
            <div class="ui-ctl listcontrol-group-add-row-title">ID:</div>
            <div class="ui-ctl ui-ctl-textbox">
                <input type="number" id="idFilter" class="ui-ctl-element list-control-input" placeholder="ID" />
            </div>
        </div>

        <div class="listcontrol-group-add-row">
            <div class="ui-ctl listcontrol-group-add-row-title">Название компании:</div>
            <div class="ui-ctl ui-ctl-textbox">
                <input type="text" id="companyNameFilter" class="ui-ctl-element list-control-input" placeholder="Название компании" />
            </div>
        </div>

        <div class="listcontrol-group-add-row">
            <div class="ui-ctl listcontrol-group-add-row-title">Страны:</div>
            <div class="ui-ctl ui-ctl-textbox">
                <select id="countryFilter" multiple="multiple" style="width: 100%;"></select>
            </div>
        </div>

        <div class="listcontrol-group-add-row">
            <div class="ui-ctl listcontrol-group-add-row-title">Имя контакта:</div>
            <div class="ui-ctl ui-ctl-textbox">
                <input type="text" id="contactNameFilter" class="ui-ctl-element list-control-input" placeholder="Имя контакта" />
            </div>
        </div>

        <div class="listcontrol-group-add-row">
            <div class="ui-ctl listcontrol-group-add-row-title">Email:</div>
            <div class="ui-ctl ui-ctl-textbox">
                <input type="text" id="emailFilter" class="ui-ctl-element list-control-input" placeholder="Email" />
            </div>
        </div>

        <div class="listcontrol-group-add-row">
            <div class="ui-ctl listcontrol-group-add-row-title">Тип партнера:</div>
            <div class="ui-ctl ui-ctl-textbox">
                <input type="text" id="partnerTypeFilter" class="ui-ctl-element list-control-input" placeholder="Тип партнера" />
            </div>
        </div>
    </div>
</div>

<script>
const data = [
    {'ID': 1, 'COMPANY_NAME': 'Company1', 'COUNTRY': 'USA,Canada', 'CONTACT_NAME': 'Alice', 'EMAIL': 'alice@email.com', 'PARTNER_TYPE': 'type1'},
    {'ID': 2, 'COMPANY_NAME': 'Company2', 'COUNTRY': 'Canada', 'CONTACT_NAME': 'Bob', 'EMAIL': 'bob@email.com', 'PARTNER_TYPE': 'type2'},
    {'ID': 3, 'COMPANY_NAME': 'Company3', 'COUNTRY': 'USA', 'CONTACT_NAME': 'Charlie', 'EMAIL': 'charlie@email.com', 'PARTNER_TYPE': 'type1'},
];

let countries = new Set();

data.forEach(item => {
    item['COUNTRY'].split(',').forEach(country => {
        countries.add(country.trim());
    });
});

$(document).ready(function() {
        // Инициализация select2
    $('#countryFilter').select2({
        placeholder: "Выберите страну",
        allowClear: true
    });
    
    // Множество выбранных элементов
    const selectedItems = new Set();
    
    // Обновление списка выбранных элементов
    function updateSelectedList() {
        $('#selectedList').text(Array.from(selectedItems).join(', '));
    }
    
    // Добавление строки в таблицу
    function addRowToTable(item) {
        const row = $('<tr></tr>')
            .append(`<td>${item['ID']}</td>`)
            .append(`<td>${item['COMPANY_NAME']}</td>`)
            .click(function() {
                const id = item['ID'];
                if (selectedItems.has(id)) {
                    selectedItems.delete(id);
                    $(this).removeClass('selected');
                } else {
                    selectedItems.add(id);
                    $(this).addClass('selected');
                }
                updateSelectedList();
            });
        $('#companyTable tbody').append(row);
    }
    
    // Фильтрация и отображение
    function filterAndDisplay() {
        $('#companyTable tbody').empty();
        const idFilter = parseInt($('#idFilter').val(), 10);
        const companyNameFilter = $('#companyNameFilter').val().toLowerCase();
        const countryFilter = $('#countryFilter').val() || [];
        const contactNameFilter = $('#contactNameFilter').val().toLowerCase();
        const emailFilter = $('#emailFilter').val().toLowerCase();
        const partnerTypeFilter = $('#partnerTypeFilter').val().toLowerCase();
    
        data.forEach(item => {
            if (idFilter && item['ID'] !== idFilter) return;
            if (companyNameFilter && !item['COMPANY_NAME'].toLowerCase().includes(companyNameFilter)) return;
            if (contactNameFilter && !item['CONTACT_NAME'].toLowerCase().includes(contactNameFilter)) return;
            if (emailFilter && !item['EMAIL'].toLowerCase().includes(emailFilter)) return;
            if (partnerTypeFilter && !item['PARTNER_TYPE'].toLowerCase().includes(partnerTypeFilter)) return;
    
            if (countryFilter.length > 0) {
                const countries = item['COUNTRY'].split(',');
                if (!countries.some(country => countryFilter.includes(country))) return;
            }
    
            addRowToTable(item);
        });
    }
    
    // Подписка на события изменения фильтров
    $('#idFilter, #companyNameFilter, #contactNameFilter, #emailFilter, #partnerTypeFilter').on('input', filterAndDisplay);
    $('#countryFilter').on('change', filterAndDisplay);
    
    // Начальное заполнение
    filterAndDisplay();
    });
</script>
</body>
</html>
