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
        #companyList {
            width: 300px;
        }
        li {
            display: flex;
            align-items: center;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div id="wrapper">
    <ul id="companyList">
        <!-- Список компаний -->
    </ul>
    
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
    $('#countryFilter').select2({
        data: Array.from(countries),
        placeholder: 'Выберите страну',
    });

    function filterAndDisplay() {
        const idFilter = $('#idFilter').val();
        const companyNameFilter = $('#companyNameFilter').val().toLowerCase();
        const selectedCountries = $('#countryFilter').val() || [];
        const contactNameFilter = $('#contactNameFilter').val().toLowerCase();
        const emailFilter = $('#emailFilter').val().toLowerCase();
        const partnerTypeFilter = $('#partnerTypeFilter').val().toLowerCase();

        const filteredData = data.filter(company => {
            const companyCountries = company['COUNTRY'].split(',').map(s => s.trim());
            return (idFilter === '' || company['ID'] === parseInt(idFilter)) &&
                   (companyNameFilter === '' || company['COMPANY_NAME'].toLowerCase().includes(companyNameFilter)) &&
                   (selectedCountries.length === 0 || selectedCountries.every(country => companyCountries.includes(country))) &&
                   (contactNameFilter === '' || company['CONTACT_NAME'].toLowerCase().includes(contactNameFilter)) &&
                   (emailFilter === '' || company['EMAIL'].toLowerCase().includes(emailFilter)) &&
                   (partnerTypeFilter === '' || company['PARTNER_TYPE'].toLowerCase().includes(partnerTypeFilter));
        });

        const ul = document.getElementById('companyList');
        ul.innerHTML = '';
        filteredData.forEach(company => {
            const li = document.createElement('li');
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.value = company['ID'];
            li.appendChild(checkbox);
            li.appendChild(document.createTextNode(' ' + company['COMPANY_NAME']));
            ul.appendChild(li);
        });
    }

    $('#idFilter').on('input', filterAndDisplay);
    $('#companyNameFilter').on('input', filterAndDisplay);
    $('#countryFilter').on('change', filterAndDisplay);
    $('#contactNameFilter').on('input', filterAndDisplay);
    $('#emailFilter').on('input', filterAndDisplay);
    $('#partnerTypeFilter').on('input', filterAndDisplay);
    
    filterAndDisplay();
});
</script>
</body>
</html>
