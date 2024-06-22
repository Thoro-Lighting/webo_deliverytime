$(() => {
    const grid = new window.prestashop.component.Grid('webo_deliverytime_shipping');
    grid.addExtension(new window.prestashop.component.GridExtensions.ReloadListExtension());
    grid.addExtension(new window.prestashop.component.GridExtensions.SortingExtension());
});