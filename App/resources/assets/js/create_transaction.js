/*
 * create_transactions.js
 * Copyright (c) 2019 james@firefly-iii.org
 *
 * This file is part of Firefly III (https://github.com/firefly-iii).
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

import CustomAttachments from "./components/transactions/CustomAttachments.vue";
import CreateTransaction from './components/transactions/CreateTransaction.vue';
import CustomDate from "./components/transactions/CustomDate.vue";
import CustomString from "./components/transactions/CustomString.vue";
import CustomTextarea from "./components/transactions/CustomTextarea.vue";
import StandardDate from "./components/transactions/StandardDate.vue";
import GroupDescription from "./components/transactions/GroupDescription.vue";
import TransactionDescription from "./components/transactions/TransactionDescription.vue";
import CustomTransactionFields from "./components/transactions/CustomTransactionFields.vue";
import PiggyBank from "./components/transactions/PiggyBank.vue";
import Tags from "./components/transactions/Tags.vue";
import Category from "./components/transactions/Category.vue";
import Amount from "./components/transactions/Amount.vue";
import ForeignAmountSelect from "./components/transactions/ForeignAmountSelect.vue";
import TransactionType from "./components/transactions/TransactionType.vue";
import AccountSelect from "./components/transactions/AccountSelect.vue";
import Budget from "./components/transactions/Budget.vue";
import CustomUri from "./components/transactions/CustomUri.vue";
import Bill from "./components/transactions/Bill.vue";

/**
 * First we will load Axios via bootstrap.js
 * jquery and bootstrap-sass preloaded in app.js
 * vue, uiv and vuei18n are in app_vue.js
 */

require('./bootstrap');

// components for create and edit transactions.
Vue.component('budget', Budget);
Vue.component('bill', Bill);
Vue.component('custom-date', CustomDate);
Vue.component('custom-string', CustomString);
Vue.component('custom-attachments', CustomAttachments);
Vue.component('custom-textarea', CustomTextarea);
Vue.component('custom-uri', CustomUri);
Vue.component('standard-date', StandardDate);
Vue.component('group-description', GroupDescription);
Vue.component('transaction-description', TransactionDescription);
Vue.component('custom-transaction-fields', CustomTransactionFields);
Vue.component('piggy-bank', PiggyBank);
Vue.component('tags', Tags);
Vue.component('category', Category);
Vue.component('amount', Amount);
Vue.component('foreign-amount', ForeignAmountSelect);
Vue.component('transaction-type', TransactionType);
Vue.component('account-select', AccountSelect);

Vue.component('create-transaction', CreateTransaction);

const i18n = require('./i18n');

let props = {};
const app = new Vue({
    i18n,
    el: "#create_transaction",
    render: (createElement) => {
        return createElement(CreateTransaction, {props: props});
    },
});
