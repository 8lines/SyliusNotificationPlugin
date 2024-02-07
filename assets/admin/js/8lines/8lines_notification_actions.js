export class NotificationActions {
  constructor() {
    this.notificationEventCodeElement = $('[data-type="notification-event-code"]')
    this.notificationActionsElement = $('[data-type="notification-actions"]')
    this.notificationActionAddElement = this.notificationActionsElement.find('[data-form-collection="add"]')
  }

  init() {
    this.updateNotificationActionEvents()

    this.notificationEventCodeElement
      .on('change', () => this.updateNotificationActionEvents())

    this.notificationActionAddElement
      .on('click', () =>
        setTimeout(() => this.updateNotificationActionEvents(), 1))
  }

  findAllNotificationActionEventElements() {
    return this.notificationActionsElement.find('[data-type="notification-action-event"]')
  }

  updateNotificationActionEvents() {
    const notificationEventCode = this.notificationEventCodeElement.val()

    $.each(this.findAllNotificationActionEventElements(),
      (index, notificationActionEventElement) => {
        if (notificationActionEventElement.value === notificationEventCode) {
          return
        }

        notificationActionEventElement.value = notificationEventCode;
        notificationActionEventElement.dispatchEvent(new Event('change', { bubbles: true }))
      })
  }
}
