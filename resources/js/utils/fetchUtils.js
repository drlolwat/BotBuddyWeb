export const fetchScripts = async () => {
    const res = await fetch('/api/user/script');
    const scripts = await res.json();
    return scripts.map(script => ({
        label: String(script.name),
        value: String(script.id),
    }));
};

export const fetchAccounts = async () => {
    const res = await fetch('/api/user/account');
    const accounts = await res.json();
    return accounts.map(account => ({
        label: String(account.email),
        value: String(account.id),
    }));
};

export const fetchAccountGroups = async () => {
    const res = await fetch('/api/user/account/group');
    const groups = await res.json();
    return groups.map(group => ({
        label: String(group.name),
        value: String(group.id),
    }));
};
