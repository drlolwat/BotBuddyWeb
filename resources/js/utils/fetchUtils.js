export const fetchScripts = async (render) => {
    const res = await fetch('/api/user/script');
    const scripts = await res.json();
    return scripts.map(script => ({
        label: script.name,
        value: script.id,
        render: () => render(script)
    }));
};

export const fetchAccounts = async (render) => {
    const res = await fetch('/api/user/account');
    const accounts = await res.json();
    return accounts.map(account => ({
        label: account.email,
        value: account.id,
        render: () => render(account)
    }));
};
