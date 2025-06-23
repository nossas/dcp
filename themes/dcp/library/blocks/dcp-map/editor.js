import { useBlockProps } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';
import { Placeholder } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import metadata from './block.json';

function Edit() {
    const blockProps = useBlockProps();

    return (
        <div {...blockProps}>
            <Placeholder label={__('Climate alerts map', 'hacklabr')} icon="location-alt"/>
        </div>
    )
}

registerBlockType(metadata.name, {
    icon: 'location-alt',
    edit: Edit,
})
