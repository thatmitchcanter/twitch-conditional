/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks } from '@wordpress/block-editor';
import { useState } from '@wordpress/element';
import {
	PanelBody,
	SelectControl,
} from '@wordpress/components';
/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit() {
	const blockProps = useBlockProps();
	const shouldDisplayBlockA = true;
	const [ live, isLive ] = useState( 'yes' );
	
	return (
		<div { ...blockProps }>
			<InspectorControls>
			<PanelBody title={ __( 'Block settings', 'twitchcraft' ) }>
				<SelectControl
					label="Stream Status"
					value={ live }
					options={ [
						{ label: 'Live on Twitch', value: 'yes' },
						{ label: 'Not Live', value: 'no' },
					] }
					onChange={ ( live ) => { isLive( live ); console.log(live); } }
					__nextHasNoMarginBottom
				/>
			</PanelBody>
		</InspectorControls>
        { live === 'yes' ? (
          <div>
            <h2>Live on Twitch</h2>
            { /* Wrapper element for conditional rendering of InnerBlocks */ }
            <div className="block-content-wrapper">
              <InnerBlocks
                allowedBlocks={['core/paragraph']}
                template={[
                  ['core/paragraph', { placeholder: 'Enter content for Block A' }],
                ]}
                templateLock="all"
              />
            </div>
          </div>
        ) : (
          <div>
            <h2>Not Live on Twitch</h2>
            { /* Wrapper element for conditional rendering of InnerBlocks */ }
            <div className="block-content-wrapper">
              <InnerBlocks
                allowedBlocks={['core/image']}
                template={[
                  ['core/image', {}],
                ]}
                templateLock="all"
              />
            </div>
          </div>
        )}
      </div>
	);
}
