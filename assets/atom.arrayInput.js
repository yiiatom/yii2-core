(function($) {
    ({
        init() {
            $(document).on('click', '.array-input-container .array-input-remove', this.onRemoveClick.bind(this));
            $(document).on('keyup', '.array-input-container tr:last-child input', this.onInputKeyUp.bind(this));
            $(document).on('drop', '.array-input-container tr:last-child input', this.onInputDrop.bind(this));
        },
        onRemoveClick(e) {
            e.preventDefault();
            this.removeRow($(e.target).closest('tr'));
        },
        onInputKeyUp(e) {
            if (e.target.value) this.addRow($(e.target).closest('.array-input-container'));
        },
        onInputDrop(e) {
            if (e.originalEvent.dataTransfer.getData('text')) this.addRow($(e.target).closest('.array-input-container'));
        },
        addRow($container) {
            let $tbody = $container.find('table tbody');
            let $row = $tbody.find('tr:last-child');
            let $new = $row.clone().appendTo($tbody);

            let key = -1;
            $tbody.find('tr').each(function() {
                let k = parseInt($(this).attr('data-key'));
                if (!isNaN(k) && k > key) {
                    key = k;
                }
            });
            key++;

            $row.attr('data-key', key).find('input').each(function() {
                let $this = $(this);
                this.name = $this.data('name').replace('[-]', '[' + key + ']');
                $this.removeAttr('data-name');
            });
            $row.find('.array-input-remove').removeClass('d-none');
            $new.find('input').each(function() {
                this.value = '';
            });
        },
        removeRow($row) {
            $row.remove();
        },
    }).init();
})(jQuery);
