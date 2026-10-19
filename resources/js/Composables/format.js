export const rupees = (value) =>
    value === null || value === undefined ? '' : 'Rs ' + Number(value).toLocaleString('en-PK');

export const shortDate = (iso) =>
    iso
        ? new Date(iso + 'T00:00:00').toLocaleDateString('en-GB', { weekday: 'short', day: 'numeric', month: 'short' })
        : '';

export const dateTime = (iso) =>
    iso ? new Date(iso).toLocaleString('en-GB', { day: 'numeric', month: 'short', hour: 'numeric', minute: '2-digit' }) : '';
