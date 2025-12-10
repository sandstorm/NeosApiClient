<?php

namespace Sandstorm\NeosApiClient;

use Sandstorm\NeosApiClient\Internal\AdaptNeosUiLoginCommand;
use Sandstorm\NeosApiClient\Internal\NodeCreation;
use Sandstorm\NeosApiClient\Internal\PreviewMode;
use Sandstorm\NeosApiClient\Internal\SecureApiUriBuilder;
use Sandstorm\NeosApiClient\Internal\SwitchBaseWorkspaceLoginCommand;
use Sandstorm\NeosApiClient\Internal\SwitchDimensionLoginCommand;
use Sandstorm\NeosApiClient\Internal\SwitchEditedNodeLoginCommand;

/**
 * Used to build URIs which directly open a NEOS backend with an already logged-in user. The URI contains a signed
 * configuration as specified by prior calls to methods in this class. It can be used to embed the Neos UI inside an
 * IFrame within another website or directly open the backend in a new tab.
 *
 * Methods in this class are meant to configure the way the Neos Ui looks and which content is visible on page load.
 * This is mostly done by preselecting the dimension ({@link dimensions}) or previewMode ({@link editPreviewMode}) and
 * by hiding possibly distracting UI elements with the diverse `hide...` methods (e.g. {@link hideMainMenu},
 * {@link hideDimensionSwitcher}, or {@link minimalUi}).
 *
 * > Hiding UI elements is just that: A way to remove visual clutter. **It is not a security feature!** All options
 * > usually accessed through the hidden UI elements are still available for the user, possibly even through other
 * > still visible UI elements.
 *
 * Usually created from a `NeosApiClient` object and directly configured and consumed.
 *
 * Instances of this type are immutable. Methods with return type `self` will return a new instance with its
 * configuration changed according to the method's semantics. Calling the methods sequentially on the same object will
 * therefore have no effect on the `buildUri` call on the initial object.
 *
 * Examples:
 *
 * Open the Document Node with id "some-id" as user "api-user"
 * ```
 *  $neosApiClient->ui->contentEditing("api-user")
 *      ->node("some-id")
 *      ->buildUri();
 * ```
 *
 * Open the Document Node with id "some-id" as user "api-user" and the dimension "language" => "en"
 * ```
 *  $neosApiClient->ui->contentEditing("api-user")
 *      ->node("some-id")
 *      ->dimensions(["language" => "en"])
 *      ->buildUri();
 * ```
 *
 * When included in another website open the Neos UI as user "api-user" and notify the surrounding page per
 * "parent.postMessage(...)" after the content was published. Show only a bare minimum of the Neos UI components.
 * ```
 *  $neosApiClient->ui->contentEditing("api-user")
 *      ->node("some-id")
 *      ->minimalUI()
 *      ->notifyOnPublish("https://your-domain.com/")
 *      ->buildUri();
 * ```
 */
final readonly class ContentEditingBuilder
{

    /**
     * # Do not call directly
     *
     * instead create a {@link NeosApiClient} and create an instance of this object with the provided methods like this:
     *
     * ```
     *  $neosApiClient = NeosApiClient::create('https://your-neos-domain.com/', 'super-secret-key');
     *  // contentEditing(...) returns an instance of this class, usually to be directly used
     *  $uri = $neosApiClient->ui->contentEditing('username')
     *    ->...
     *    ->buildUri();
     * ```
     *
     * ---
     *
     * # Internal/Implementation Documentation
     *
     * Create an instance of this Builder with its complete configuration in the provided {@link SecureApiUriBuilder}.
     * Methods in this class will create modified versions of the {@link SecureApiUriBuilder} and return a newly created
     * instance of this class with the new, different {@link SecureApiUriBuilder} inside.
     *
     * All actual state rests in the {@link SecureApiUriBuilder}. Wrapped by this class.
     *
     * @param SecureApiUriBuilder $apiUriBuilder
     */
    public function __construct(private SecureApiUriBuilder $apiUriBuilder)
    {
    }

    /**
     * Change the base workspace the user will publish changes into. By default, this is `live`.
     *
     * Should the chosen workspace not yet exist, it will be forked from `live` when the generated URI is opened.
     *
     * This will not prevent the user from switching the base workspace before publishing.
     *
     * @param string $workspace the name of the base workspace changes will be published into.
     * @return self
     */
    public function publishInto(string $workspace): self
    {
        return new self($this->apiUriBuilder->withCommand(new SwitchBaseWorkspaceLoginCommand($workspace)));
    }

    /**
     * If set the Neos UI will open the document with the given dimension If the user should only edit the given
     * dimension also use {@link hideDimensionSwitcher} to remove the dimension switcher from the UI.
     *
     * @see hideDimensionSwitcher
     * @param array<string,string> $dimensions
     * @return self
     */
    public function dimensions(array $dimensions): self
    {
        return new self($this->apiUriBuilder->withCommand(new SwitchDimensionLoginCommand($dimensions)));
    }

    /**
     * Open the Neos UI with the document node with the given `$nodeId` displayed.
     *
     * The nodeId is not validated before opening the generated URI. When opening the URI the following will happen:
     *
     * If `$createIfNotExisting` is not null and the node with id `$nodeId` does not exist it will be created according
     * to the {@link NodeCreation} object provided.
     *
     * If `$createIfNotExisting` is null or undefined and the node does not yet exist (in the chosen dimensions), or if
     * the `$createIfNotExisting` is non-null but no node with `$createIfNotExisting->parentNodeId` exists, the call
     * will return a 404 error instead of opening the Neos UI.
     *
     * ---
     *
     * This only sets the initially opened node and does limit the users ability to change the edited node in the
     * content tree in any way.
     *
     * Providing the id of a non document node will result in undefined behaviour. This behaviour may change in future
     * versions of this library without a deprecation notice or even changelog entry.
     *
     * @param string $nodeId the id of the document node to be initially opened
     * @param NodeCreation|null $createIfNotExisting Options to create the node to be opened if it does not yet exist
     * @return self
     */
    public function node(string $nodeId, ?NodeCreation $createIfNotExisting = null): self
    {
        return new self($this->apiUriBuilder->withCommand(new SwitchEditedNodeLoginCommand($nodeId, $createIfNotExisting)));
    }

    /**
     * Hide the Neos UI main Menu (or more exactly: the button in the top left corner to open it).
     *
     * > **This is not a security feature!** The user will still be allowed to enter all menus accessible through that
     * > menu. We did not try to protect against attempts to do so in any way!
     *
     * @return self
     * @see dimensions
     */
    public function hideMainMenu(): self
    {
        return new self($this->apiUriBuilder->withCommand(new AdaptNeosUiLoginCommand(showMainMenu: false)));
    }

    /**
     * Hide the left sidebar. This will hide both the document tree and the content tree.
     *
     * > **This is not a security feature!** The user will still be allowed to the edited node. Only the UI document
     * > tree as the most convenient way to do so will be removed. Changing the edited node through e.g. links on the
     * > page is still easily possible.
     * > We did not try to protect against attempts to change it any other way!
     *
     * @return self
     * @see dimensions
     */
    public function hideLeftSideBar(): self
    {
        return new self($this->apiUriBuilder->withCommand(new AdaptNeosUiLoginCommand(showLeftSideBar: false)));
    }

    public function hideDocumentTree(): self
    {
        return new self($this->apiUriBuilder->withCommand(new AdaptNeosUiLoginCommand(showDocumentTree: false)));
    }

    /**
     * This will hide the Component to switch the preview mode within the Neos UI. Can be used together with the
     * {@link editPreviewMode} method to fix a specific preview mode.
     *
     * > **This is not a security feature!** The user will still be allowed to change preview modes. Only the UI element
     * > to do so will be removed. We did not try to protect against attempts to change it any other way!
     *
     * @return self
     * @see dimensions
     */
    public function hideEditPreviewDropDown(): self
    {
        return new self($this->apiUriBuilder->withCommand(new AdaptNeosUiLoginCommand(showEditPreviewDropDown: false)));
    }

    /**
     * This will hide the Component to switch dimensions within the Neos UI. Should be used together with the
     * {@link dimensions} method to fix the dimension to a certain value.
     *
     * > **This is not a security feature!** The user will still be allowed to change dimensions. Only the UI element to do
     * > so will be removed. We did not try to protect against attempts to change it any other way!
     *
     * @return self
     * @see dimensions
     */
    public function hideDimensionSwitcher(): self
    {
        return new self($this->apiUriBuilder->withCommand(new AdaptNeosUiLoginCommand(showDimensionSwitcher: false)));
    }

    /**
     * When the generated URI is opened in an IFrame and this option is set, the website containing the IFrame will be
     * notified through `postMessage()`, when publishing finished in Neos.
     *
     * The message has the following form:
     *
     * ```
     *  {
     *      "type": "@neos/neos-ui/CR/Publishing/FINISHED"
     *  }
     * ```
     *
     * Example code to listen for the event:
     *
     * ```
     *  window.addEventListener("message", (event) => {
     *      if(event.data.type === "@neos/neos-ui/CR/Publishing/FINISHED") {
     *          // publishing finished inside iframe
     *      }
     *  });
     * ```
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/API/Window/postMessage
     * @param string $targetOrigin The origin of the receiving window including the scheme, hostname and port.
     *                             See https://developer.mozilla.org/en-US/docs/Web/API/Window/postMessage#targetorigin
     *                             for a longer explanation of this parameter.
     * @return self
     */
    public function notifyOnPublish(string $targetOrigin): self
    {
        return new self($this->apiUriBuilder->withCommand(new AdaptNeosUiLoginCommand(notifyOnPublishTarget: $targetOrigin)));
    }

    /**
     * The generated URL will open a Neos UI with only those components and parts *required* to edit the content.
     *
     *  > Attention: the exact result this call will produce will probably change once more ui adaptions are
     *  > implemented. It will continue to allow editing the content and publishing the changes while removing or
     *  > altering everything not strictly required to do this.
     *  >
     *  > Should you need absolute control about what is shown and what isn't, call the specific `hide...()` methods
     *  > instead of relying on this shortcut.
     *
     *  > **This is not a security feature!** The user will still be allowed to change all settings usually changed through
     *  > the hidden components. Only the UI elements to do so will be removed. We did not try to protect against
     *  > attempts to change it any other way!
     *
     * This is currently the same as calling
     * ```
     *  $contentEditingBuilder
     *      ->hideMainMenu()
     *      ->hideLeftSideBar()
     *      ->hideEditPreviewDropDown()
     *      ->hideDimensionSwitcher()
     * ```
     *
     * @return self
     */
    public function minimalUi(): self
    {
        // to be extended once more elements can be hidden
        return new self($this->apiUriBuilder->withCommand(new AdaptNeosUiLoginCommand(
            showMainMenu: false,
            showLeftSideBar: false,
            showDocumentTree: false,
            showEditPreviewDropDown: false,
            showDimensionSwitcher: false
        )));
    }

    /**
     * Set the initial preview mode the Neos UI will be opened in. This will not limit the users ability to change the
     * preview mode in the Neos UI any way.
     *
     * @param PreviewMode $previewMode The initial preview Mode
     * @return self
     */
    public function editPreviewMode(PreviewMode $previewMode): self
    {
        return new self($this->apiUriBuilder->withCommand(new AdaptNeosUiLoginCommand(
            previewMode: $previewMode,
        )));
    }

    // TODO: specify "short amount of time" once it is actually decided, seems to currently be 10 minutes
    /**
     * Generate the Uri containing the signed configuration created before the call.
     *
     * The generated URI will only be valid a short amount of time, starting the instant this method is called.
     *
     * Calling this method again with the same object will create a new URI with a new validity time span.
     *
     * @return string the URI to the Neos backend with the signed configuration.
     */
    public function buildUri()
    {
        return $this->apiUriBuilder->buildUri('/api/embeddedBackend/open');
    }
}
