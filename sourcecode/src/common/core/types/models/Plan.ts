export class Plan {
    id: number;
    name: string;
    amount: string;
    currency: string;
    currency_symbol = '$';
    interval: 'once'|'day'|'week'|'month'|'year' = 'month';
    interval_count = 1;
    parent_id: number = null;
    parent?: Plan;
    permissions: object = {};
    recommended: 0|1 = 0;
    show_permissions: 0|1 = 0;
    free: 0|1 = 0;
    custom: 0|1 = 0;
    position = 0;
    features: string[] = [];
    available_space: number;

    constructor(params: Object = {}) {
        for (let name in params) {
            this[name] = params[name];
        }
    }
}